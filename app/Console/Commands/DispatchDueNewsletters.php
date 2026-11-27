<?php

namespace App\Console\Commands;

use App\Services\NewsletterDispatch;
use Illuminate\Console\Command;

class DispatchDueNewsletters extends Command
{
    protected $signature = 'newsletter:dispatch-due';

    protected $description = 'Fällige, freigegebene Newsletter in die Kundenmail-Warteschlange einreihen';

    public function handle(NewsletterDispatch $dispatch): int
    {
        $dispatch->queueDue();

        return self::SUCCESS;
    }
}
