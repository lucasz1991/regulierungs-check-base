<?php

namespace App\Jobs\ClaimRating;

use App\Models\ClaimRating;
use App\Models\User;
use App\Notifications\ClaimRatingMissingEmailVerificationReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendClaimRatingReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        // 1) fehlende Mail-Verifizierung
        $this->sendMissingEmailVerificationReminders();

        // 2) weitere Reminder-Typen später hier ergänzen
        // $this->sendVerificationMissingDocumentsReminders();
    }

    /**
     * Reminder an User, die:
     * - keine verifizierte E-Mail haben
     * - mindestens ein ClaimRating haben,
     *   das "rated" ist, aber nicht öffentlich
     */
    protected function sendMissingEmailVerificationReminders(): void
    {
        User::query()
            ->whereNull('email_verified_at')
            ->whereHas('claimRatings', function ($q) {
                $q->where('is_public', false)
                  ->where('status', ClaimRating::STATUS_RATED);
            })
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    /** @var \App\Models\User $user */
                    $claimRatings = $user->claimRatings()
                        ->where('is_public', false)
                        ->where('status', ClaimRating::STATUS_RATED)
                        ->get();

                    if ($claimRatings->isEmpty()) {
                        continue;
                    }

                    // Notification statt Mail::to()
                    $user->notify(
                        new ClaimRatingMissingEmailVerificationReminder($claimRatings)
                    );
                }
            });
    }
}
