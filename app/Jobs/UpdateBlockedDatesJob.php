<?php

namespace App\Jobs;

use App\Models\ShelfRental;
use App\Models\Shelve;
use App\Models\ShelfBlockedDate;
use App\Models\BlockedDate;
use App\Models\Setting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateBlockedDatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shelfRental;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\ShelfRental $shelfRental
     */
    public function __construct(ShelfRental $shelfRental)
    {
        $this->shelfRental = $shelfRental;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $shelfId = $this->shelfRental->shelf->id;
        $retailSpaceId = $this->shelfRental->shelf->retail_space_id;
        $rentalStart = $this->shelfRental->rental_start;
        $rentalEnd = $this->shelfRental->rental_end;
    
        // Zuerst alle Tage der Mietzeit in die Tabelle `shelf_blocked_dates` eintragen
        $currentDate = new \DateTime($rentalStart);
        $endDate = new \DateTime($rentalEnd);
    
        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m-d');
    
            // Eintrag in ShelfBlockedDates
            DB::transaction(function () use ($shelfId, $retailSpaceId, $formattedDate) {
                $blockedDate = ShelfBlockedDate::updateOrCreate(
                    [
                        'shelf_id' => $shelfId,
                        'retail_space_id' => $retailSpaceId,
                        'blocked_date' => $formattedDate,
                    ],
                    [
                        'updated_at' => Carbon::now(),
                    ]
                );
    
                // Nur loggen, wenn ein neuer Eintrag erstellt wurde
                if ($blockedDate->wasRecentlyCreated) {
                    logger()->info('ShelfBlockedDate created:', [
                        'shelfId' => $shelfId,
                        'retailSpaceId' => $retailSpaceId,
                        'blockedDate' => $formattedDate,
                    ]);
                }
            });
    
            $currentDate->modify('+1 day');
        }
    
        // Zeitraum für die Prüfung (1 Monat vor und nach der Mietzeit)
        $from = Carbon::parse($rentalStart)->subMonth(); // 1 Monat vor dem Buchungsstart
        $to = Carbon::parse($rentalEnd); 
        $totalShelves = Shelve::where('retail_space_id', $retailSpaceId)->get();
    
        // Alle Tage im Zeitraum prüfen
        $allDates = Carbon::parse($from)->daysUntil($to);
    
        foreach ($allDates as $startDate) {
            $startDateFormatted = $startDate->format('Y-m-d');
    
            // Prüfen, ob es für diesen Tag ein Regal gibt, das für den gesamten Zeitraum verfügbar ist
            $this->checkAndBlockShelf($retailSpaceId, $startDateFormatted, $rentalEnd);
        }
    }
    
    /**
     * Prüft, ob es für die drei Perioden (21, 14, 7 Tage) blockierte Regale gibt
     * und fügt einen Eintrag in die BlockedDate-Tabelle hinzu, falls keine Regale verfügbar sind.
     *
     * @param int $retailSpaceId
     * @param string $startDate
     * @param string $endDate
     * @return void
     */
    private function checkAndBlockShelf($retailSpaceId, $startDate, $endDate)
    {
        $periods = [21, 14, 7]; // Die zu prüfenden Perioden (21 Tage, 14 Tage, 7 Tage)
    
     
        $disabledWeekdays = Setting::where('type', 'disabled_day')->pluck('value')->map(fn($day) => (int)$day)->toArray();
        $holidays = Setting::where('type', 'holiday')->pluck('value')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))->toArray();

        foreach ($periods as $period) {
            // Starte die Enddatumsberechnung
            $currentDate = Carbon::parse($startDate);
            $daysAdded = 1;

            while ($daysAdded < $period) {
                $currentDate->addDay();

                // Überspringe deaktivierte Wochentage und Feiertage
                if (in_array($currentDate->dayOfWeek, $disabledWeekdays) || in_array($currentDate->format('Y-m-d'), $holidays)) {
                    continue;
                }

                $daysAdded++;
            }

            $endPeriodDate = $currentDate->format('Y-m-d');
    
              // Hier prüfst du zuerst, ob bereits ein BlockedDate für die Periode existiert
                $existingBlockedDate = BlockedDate::where('retail_space_id', $retailSpaceId)
                ->where('blocked_date', $startDate)
                ->where('blocked_period', $period)
                ->first();
                if ($existingBlockedDate) {
                    // Wenn die Blockierung bereits existiert, überspringen
                    continue;
                }

            // Prüfen, ob es für diese Periode ein Regal gibt, das nicht blockiert ist
            $isAvailable = $this->checkShelfAvailability($retailSpaceId, $startDate, $endPeriodDate);
    
            if (!$isAvailable) {
                logger()->info("!!!!!!!!   Shelf is not available for period {$period} days from {$startDate} to {$endPeriodDate}");
            }else{
                logger()->info("Shelf  available for period {$period} days from {$startDate} to {$endPeriodDate}");
                // Wenn ein Regal verfügbar ist, wird die Schleife beendet (keine weiteren Perioden prüfen)
                break;
            }
    
            // Wenn kein Regal verfügbar ist, einen BlockedDate-Eintrag für diese Periode anlegen
            DB::transaction(function () use ($retailSpaceId, $startDate, $period) {
                
                $blockedDate = BlockedDate::updateOrCreate(
                    [
                        'retail_space_id' => $retailSpaceId,
                        'blocked_date' => $startDate,
                        'blocked_period' => $period, 
                    ],
                    [
                        'updated_at' => Carbon::now(),
                    ]
                );
    
                if ($blockedDate->wasRecentlyCreated) {
                    logger()->info("Blocked date created for date $startDate and period $period:", [
                        'date' => $startDate,
                        'retailSpaceId' => $retailSpaceId,
                        'period' => $period,
                    ]);
                }
            });
        }
    }
    
    
    /**
     * Prüft, ob mindestens ein Regal für den gesamten Zeitraum verfügbar ist.
     *
     * @param int $retailSpaceId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    private function checkShelfAvailability($retailSpaceId, $startDate, $endDate)
    {
        // Überprüfen, ob es Regale gibt, die für den gesamten Zeitraum verfügbar sind
        $availableShelves = DB::table('shelves')
            ->leftJoin('shelf_blocked_dates', function ($join) use ($startDate, $endDate) {
                // Join Bedingung: Regal darf keine Blockierung im angegebenen Zeitraum haben
                $join->on('shelves.id', '=', 'shelf_blocked_dates.shelf_id')
                    ->whereBetween('shelf_blocked_dates.blocked_date', [$startDate, $endDate]);
            })
            ->where('shelves.retail_space_id', $retailSpaceId)
            ->groupBy('shelves.id')
            ->havingRaw('COUNT(shelf_blocked_dates.shelf_id) = 0')  // Keine Blockierungen für dieses Regal im Zeitraum
            ->pluck('shelves.id');
    
        // Gibt zurück, ob es mindestens ein Regal gibt, das im gesamten Zeitraum verfügbar ist
        return $availableShelves->isNotEmpty();
    }
}
