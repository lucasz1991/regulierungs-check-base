<?php

namespace App\Http\Controllers\Participant\Promotion;

use App\Http\Controllers\Controller;
use App\Models\PromotionParticipation;
use App\Models\PromotionTicket;
use App\Services\Auth\CustomerAccountService;
use App\Services\Promotion\PromotionQrCodeService;
use App\Services\Promotion\PromotionTicketQrSigner;
use Illuminate\Http\Response;
use RuntimeException;

final class TicketQrController extends Controller
{
    public function __invoke(
        PromotionParticipation $participation,
        CustomerAccountService $accounts,
        PromotionTicketQrSigner $signer,
        PromotionQrCodeService $qrCodes,
    ): Response {
        abort_unless((int) $participation->user_id === (int) auth()->id(), 404);

        try {
            $accounts->assertAndNormalizeParticipant(auth()->user());
        } catch (RuntimeException) {
            abort(404);
        }

        $ticket = PromotionTicket::query()
            ->where('participation_id', $participation->getKey())
            ->where('user_id', auth()->id())
            ->firstOrFail();
        $status = $ticket->status instanceof \BackedEnum ? $ticket->status->value : (string) $ticket->status;
        abort_unless($status === 'ready', 409, 'Dieses Ticket kann nicht mehr gescannt werden.');

        return response($qrCodes->svg($signer->payload($ticket)), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, private, max-age=0',
            'Pragma' => 'no-cache',
            'Referrer-Policy' => 'no-referrer',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
            'Content-Disposition' => 'inline; filename="gluecksrad-ticket.svg"',
        ]);
    }
}
