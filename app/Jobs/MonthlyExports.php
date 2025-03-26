<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\ShelfRental;
use App\Models\ShelfRentalExtension;
use App\Models\Payout;
use App\Models\Customer;
use App\Models\Setting;

class MonthlyExports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $year = Carbon::now()->subMonth()->format('Y');
        $month = Carbon::now()->subMonth()->format('m');

        // **Einstellungen abrufen**
        $autoExport = Setting::where('type', 'exports')->where('key', 'auto_export')->pluck('value')->first() ?? false;
        $exportEmail = Setting::where('type', 'exports')->where('key', 'export_email')->pluck('value')->first() ?? null;

        if (!$autoExport || !$exportEmail) {
            \Log::info("📌 Automatische Exporte deaktiviert oder keine E-Mail-Adresse hinterlegt.");
            return;
        }

        // **Dateien generieren**
        $files = [
            'Buchungen' => $this->exportBookings($year, $month),
            'Verlängerungen' => $this->exportBookingExtends($year, $month),
            'Auszahlungen' => $this->exportPayouts($year, $month),
            'Kunden' => $this->exportCustomers($year, $month),
        ];

        // **Nicht vorhandene Dateien entfernen**
        $files = array_filter($files);

        if (empty($files)) {
            \Log::info("❌ Keine Daten für den Export gefunden.");
            return;
        }
        $exportEmail2 = "info@minifinds.de";

        // **E-Mail versenden**
        Mail::raw("Die monatlichen Exporte sind im Anhang.", function ($message) use ($exportEmail2, $files) {
            $message->to($exportEmail2)
                    ->subject('📊 Monatliche Exporte');

            foreach ($files as $file) {
                $message->attach($file);
            }
        });

        // **E-Mail versenden**
        Mail::raw("Die monatlichen Exporte sind im Anhang.", function ($message) use ($exportEmail, $files) {
            $message->to($exportEmail)
                    ->subject('📊 Monatliche Exporte');

            foreach ($files as $file) {
                $message->attach($file);
            }
        });

        \Log::info("📧 Export-E-Mail erfolgreich an $exportEmail gesendet.");
    }

    private function exportBookings($year, $month)
    {
        $shelfRentals = ShelfRental::whereYear('created_at', $year)
                                   ->whereMonth('created_at', $month)
                                   ->get();

        if ($shelfRentals->isEmpty()) {
            return null;
        }

        $csv = $this->generateCsv($shelfRentals, [
            'Regalbuchungsnummer', 'Kunde', 'Gesamtbetrag', 'Mietbeginn', 'Mietende', 'Rechnungsnummer', 'Erstellt am'
        ], function ($shelfRental) {
            return [
                $this->sanitizeString($shelfRental->id),
                $this->sanitizeString(optional($shelfRental->customer)->user->id . ' ' . optional($shelfRental->customer)->first_name . ' ' . optional($shelfRental->customer)->last_name),
                str_replace('.', ',', sprintf('%.2f', (float)$shelfRental->total_price)),
                $this->sanitizeString(Carbon::parse($shelfRental->rental_start)->format('d.m.Y')),
                $this->sanitizeString(Carbon::parse($shelfRental->rental_end)->format('d.m.Y')),
                $this->sanitizeString(optional($shelfRental->invoices->first())->id ?? 'Keine Rechnung'), // Falls keine Rechnung existiert
                $this->sanitizeString(Carbon::parse($shelfRental->created_at)->format('d.m.Y')),
            ];
        });

        return $this->saveCsv($csv, "minifinds-regalbuchungen_export_{$year}-{$month}.csv");
    }

    private function exportBookingExtends($year, $month)
    {
        $extensions = ShelfRentalExtension::whereYear('created_at', $year)
                                          ->whereMonth('created_at', $month)
                                          ->where('is_admin', false)
                                          ->get();

        if ($extensions->isEmpty()) {
            return null;
        }

        $csv = $this->generateCsv($extensions, [
            'Verlaengerungs-Buchungs-ID', 'Regal-Buchungs-ID', 'Kunde', 'Altes Mietende', 'Neues Mietende', 'Bezahlt', 'Rechnungsnummer' , 'Verlaengert am'
        ], function ($extension) {
            return [
                $this->sanitizeString($extension->id), 
                $this->sanitizeString($extension->shelf_rental_id), 
                $this->sanitizeString(optional($extension->shelfRental->customer)->first_name . ' ' . optional($extension->shelfRental->customer)->last_name),
                $this->sanitizeString(Carbon::parse($extension->previous_end_date)->format('d.m.Y')),
                $this->sanitizeString(Carbon::parse($extension->new_end_date)->format('d.m.Y')),
                $this->sanitizeString(number_format($extension->amount_paid, 2, ',', '.')),
                $this->sanitizeString($extension->invoice_id), 
                $this->sanitizeString(Carbon::parse($extension->created_at)->format('d.m.Y H:i')),
            ];
        });

        return $this->saveCsv($csv, "minifinds-verlaengerungen_export_{$year}-{$month}.csv");
    }

    private function exportPayouts($year, $month)
    {
        $payouts = Payout::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where('status', true)
                        ->get();

        if ($payouts->isEmpty()) {
            return null;
        }

        $csv = $this->generateCsv($payouts, [
            'Auszahlungs-ID', 'Kunde', 'Betrag', 'Angefordert am', 'Genehmigt am', 'Zahlungsmethode', 'Auszahlungsdetails'
        ], function ($payout) {
            // Kundeninformationen abrufen
            $customerInfo = optional($payout->customer)->user->id . ' ' . optional($payout->customer)->first_name . ' ' . optional($payout->customer)->last_name;
        
            // Auszahlungsbetrag und Datum formatieren
            $amount = number_format((float)$payout->amount, 2, ',', '.');
            $requestedAt = Carbon::parse($payout->created_at)->format('d.m.Y H:i');
            $approvedAt = Carbon::parse($payout->updated_at)->format('d.m.Y H:i');
        
            // Zahlungsmethode ermitteln
            if (isset($payout->payout_details['paypal_email'])) {
                $paymentMethod = 'PayPal';
                $paymentDetails = 'PayPal: ' . $payout->payout_details['paypal_email'];
            } elseif (isset($payout->payout_details['iban'])) {
                $paymentMethod = 'Bankueberweisung';
                $paymentDetails = 'IBAN: ' . $payout->payout_details['iban'] . ', BIC: ' . $payout->payout_details['bic'];
            } else {
                $paymentMethod = 'Unbekannt';
                $paymentDetails = '❌ Keine Auszahlungsdetails verfügbar';
            }
        
            return [
                $this->sanitizeString($payout->id),
                $this->sanitizeString($customerInfo),
                $this->sanitizeString($amount),
                $this->sanitizeString($requestedAt),
                $this->sanitizeString($approvedAt),
                $this->sanitizeString($paymentMethod),
                $paymentDetails,
            ];
        });

        return $this->saveCsv($csv, "minifinds-auszahlungen_export_{$year}-{$month}.csv");
    }

    private function exportCustomers($year, $month)
    {
        $customers = Customer::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->get();

        if ($customers->isEmpty()) {
            return null;
        }

        $csv = $this->generateCsv($customers, [
            'Kunden-ID', 'Name', 'E-Mail', 'Telefon', 'Straße', 'Stadt', 'Bundesland', 'PLZ', 'Land', 'Registrierungsdatum'
        ], function ($customer) {
            return [
                $this->sanitizeString(optional($customer->user)->id), 
                $this->sanitizeString($customer->first_name . ' ' . $customer->last_name),
                optional($customer->user)->email, 
                $this->sanitizeString($customer->phone_number),
                $this->sanitizeString($customer->street),
                $this->sanitizeString($customer->city),
                $this->sanitizeString($customer->state),
                $this->sanitizeString($customer->postal_code),
                $this->sanitizeString($customer->country),
                $this->sanitizeString(Carbon::parse($customer->created_at)->format('d.m.Y H:i')),
            ];
        });

        return $this->saveCsv($csv, "minifinds-kunden_export_{$year}-{$month}.csv");
    }


    private function generateCsv($data, $headers, $callback)
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers, ';');

        foreach ($data as $row) {
            fputcsv($handle, $callback($row), ';');
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return $csvContent;
    }

    private function saveCsv($csv, $filename)
    {
        Storage::disk('local')->put("exports/$filename", $csv);
        return storage_path("app/exports/$filename");
    }

    /**
     * Entfernt unerwünschte Zeichen aus einem String.
     */
    private function sanitizeString($string)
    {
        if (!is_string($string)) {
            return $string;
        }
    
        // Zuerst Umlaute explizit ersetzen
        $umlautMapping = [
            'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
            'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue',
        ];
        $string = strtr($string, $umlautMapping);
    
        // Alle übrigen diakritischen Zeichen (z.B. é, è, ê, etc.) transliterieren
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    
        // Entfernt nicht-druckbare Zeichen & Steuerzeichen
        $string = preg_replace('/[\x00-\x1F\x7F]/', '', $string);
    
        // Erlaubt Buchstaben, Zahlen, Satzzeichen & Leerzeichen
        $string = preg_replace('/[^A-Za-z0-9\s.,;:!?\-()€$]/', '', $string);
    
        return trim($string);
    }
}
