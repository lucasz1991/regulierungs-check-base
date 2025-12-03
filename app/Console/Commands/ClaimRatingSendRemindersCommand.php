<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ClaimRating\SendClaimRatingReminders;

class ClaimRatingSendRemindersCommand extends Command
{
    /**
     * Name des Artisan Befehls.
     */
    protected $signature = 'claimratings:send-reminders';

    protected $description = 'Versendet Erinnerungs-Notifications zu ClaimRatings (z. B. fehlende E-Mail-Verifizierung).';

    public function handle(): int
    {
        SendClaimRatingReminders::dispatch();

        $this->info('Job "SendClaimRatingReminders" wurde in die Queue gestellt.');

        return Command::SUCCESS;
    }
}
