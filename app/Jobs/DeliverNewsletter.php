<?php

namespace App\Jobs;

use App\Services\NewsletterDispatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class DeliverNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public int $campaignId, public int $deliveryId) {}

    public function handle(NewsletterDispatch $dispatch): void
    {
        $dispatch->deliver($this->campaignId, $this->deliveryId);
    }

    public function failed(Throwable $exception): void
    {
        app(NewsletterDispatch::class)->jobFailed($this->campaignId, $this->deliveryId);
    }
}
