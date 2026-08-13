<?php

namespace App\Enums;

enum PromotionWinStatus: string
{
    case Issued = 'issued';
    case Bound = 'bound';
    case Confirmed = 'confirmed';
    case Fulfilled = 'fulfilled';
    case Disputed = 'disputed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';

    public function isFinal(): bool
    {
        return in_array($this, [self::Fulfilled, self::Cancelled], true);
    }
}
