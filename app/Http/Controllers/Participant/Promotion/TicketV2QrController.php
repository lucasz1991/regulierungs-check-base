<?php

namespace App\Http\Controllers\Participant\Promotion;

use App\Http\Controllers\Controller;
use App\Models\PromotionTicket;
use App\Services\Promotion\PromotionQrCodeService;
use App\Services\Promotion\PromotionTicketQrSigner;
use Illuminate\Http\Response;

final class TicketV2QrController extends Controller
{
    public function __invoke(PromotionTicket $ticket, PromotionTicketQrSigner $signer, PromotionQrCodeService $qrCodes): Response
    {
        abort_unless((int) $ticket->user_id === (int) auth()->id() && $ticket->status->value === 'ready', 404);
        return response($qrCodes->svg($signer->payload($ticket)), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8', 'Cache-Control' => 'no-store, no-cache, must-revalidate, private, max-age=0',
            'Pragma' => 'no-cache', 'Referrer-Policy' => 'no-referrer', 'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox", 'Content-Disposition' => 'inline; filename="gluecksrad-ticket.svg"',
        ]);
    }
}
