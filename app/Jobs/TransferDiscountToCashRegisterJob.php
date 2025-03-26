<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\ShelfRental;
use App\Models\Setting;

class TransferDiscountToCashRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shelfRental;
    protected $apiSettings;

    /**
     * Create a new job instance.
     */
    public function __construct(ShelfRental $shelfRental)
    {
        $this->shelfRental = $shelfRental;
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // API Einstellungen abrufen
        $apiSettings = \App\Models\Setting::where('type', 'api')
        ->where('key', 'like', 'cash_register%')
        ->pluck('value', 'key')
        ->mapWithKeys(fn($value, $key) => [$key => $value])
        ->toArray();

        $apiToken = $apiSettings['cash_register_api_key'];
        $apiUrl = $apiSettings['cash_register_api_url'];
        $client = new Client();
        $products = $this->shelfRental->products()->where('status', 2)->get();
        foreach ($products as $product) {
            // Prüfen, ob das Produkt eine Kassen-ID hat
            if (!$product->cash_register_id) {
                Log::error('Produkt hat keine cash_register_id und kann nicht aktualisiert werden.', ['product_id' => $product->id]);
                continue;
            }


            // Preis bestimmen: Standardpreis oder Rabattpreis
            $finalPrice = ($product->discount > 0) ? $product->discount_price : $product->price;

            // JSON-Anfrage für das Preisupdate vorbereiten
            $discountData = [
                'path' => 'sale.0.price',
                'value' => (string) $finalPrice, // Entweder discount_price oder price
            ];

            $jsonBody = json_encode($discountData);

            Log::info('Sende Rabatt-Daten an API:', [
                'product_id' => $product->id,
                'finalPrice' => $finalPrice,
                'jsonBody' => $jsonBody,
            ]);

            try {
                $maxRetries = 100; // Maximale Anzahl von Wiederholungen
                $retryDelay = 5; // Wartezeit in Sekunden
                $attempts = 0;

                do {
                    $attempts++;

                    try {
                        $response = $client->request('PATCH', $apiUrl . 'articles/' . $product->cash_register_id, [
                            'body' => $jsonBody,
                            'headers' => [
                                'Authorization' => 'Bearer ' . $apiToken,
                                'accept' => 'application/json',
                                'content-type' => 'application/json',
                            ],
                        ]);

                        if ($response->getStatusCode() === 200) {
                            Log::info('Rabatt erfolgreich an die Kasse gesendet.', [
                                'product_id' => $product->id,
                                'response' => json_decode($response->getBody(), true),
                            ]);
                            break; // Erfolgreich, also aus der Schleife aussteigen
                        } else {
                            Log::error('Fehler beim Senden des Rabatts:', [
                                'status' => $response->getStatusCode(),
                                'product_id' => $product->id,
                            ]);
                        }
                    } catch (\GuzzleHttp\Exception\ClientException $e) {
                        if ($e->getResponse() && $e->getResponse()->getStatusCode() === 429) {
                            Log::warning('Rate-Limit erreicht. Warte 5 Sekunden...', [
                                'product_id' => $product->id,
                                'message' => $e->getMessage(),
                            ]);

                            if ($attempts < $maxRetries) {
                                sleep($retryDelay);
                            } else {
                                Log::error('Maximale Anzahl von Wiederholungen erreicht. Anfrage abgebrochen.');
                                break;
                            }
                        } else {
                            Log::error('API-Anfrage für Rabatt fehlgeschlagen:', [
                                'product_id' => $product->id,
                                'message' => $e->getMessage(),
                                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                            ]);
                            break;
                        }
                    }

                } while ($attempts < $maxRetries);
            } catch (\Exception $e) {
                Log::error('Unerwarteter Fehler bei der Rabatt-API-Anfrage:', [
                    'product_id' => $product->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
