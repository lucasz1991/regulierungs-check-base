<?php

namespace App\Http\Controllers\Participant\Promotion;

use App\Http\Controllers\Controller;
use App\Services\Promotion\PromotionWinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RedemptionController extends Controller
{
    public const TOKEN_SESSION_KEY = 'promotion.redemption_token';

    public function redeem(
        Request $request,
        PromotionWinService $promotionWinService,
        string $token,
    ): RedirectResponse {
        if (! preg_match('/\A[A-Za-z0-9_-]{32,128}\z/', $token)) {
            $request->session()->forget(self::TOKEN_SESSION_KEY);

            return to_route('promotion.claim')
                ->with('promotion_error', 'Dieser Gewinn-Link ist ungültig. Bitte wende dich an das Promotion-Team.');
        }

        try {
            $promotionWinService->inspectToken($token);
        } catch (Throwable $exception) {
            Log::warning('Ungültiger oder nicht mehr einlösbarer Promotion-Link aufgerufen.', [
                'exception_class' => $exception::class,
            ]);
            $request->session()->forget(self::TOKEN_SESSION_KEY);

            return to_route('promotion.claim')
                ->with('promotion_error', 'Dieser Gewinn-Link ist abgelaufen, bereits verwendet oder ungültig. Bitte wende dich an das Promotion-Team.');
        }

        $request->session()->put(self::TOKEN_SESSION_KEY, $token);

        if (! $request->user()) {
            return to_route('promotion.claim');
        }

        return $this->bindAuthenticatedUser($request, $promotionWinService, $token);
    }

    public function claim(Request $request): View
    {
        return view('participant.promotion.claim', [
            'hasPromotionToken' => $request->session()->has(self::TOKEN_SESSION_KEY),
        ]);
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(self::TOKEN_SESSION_KEY);

        return to_route('home');
    }

    private function bindAuthenticatedUser(
        Request $request,
        PromotionWinService $promotionWinService,
        string $token,
    ): RedirectResponse {
        try {
            $participation = $promotionWinService->bindToken($token, $request->user(), $this->accessContext($request));
        } catch (Throwable $exception) {
            Log::warning('Promotion-Gewinnbindung fehlgeschlagen.', [
                'exception_class' => $exception::class,
            ]);
            $request->session()->forget(self::TOKEN_SESSION_KEY);

            return to_route('promotion.claim')
                ->with('promotion_error', 'Der Gewinn konnte nicht zugeordnet werden. Der Link ist möglicherweise abgelaufen oder bereits verwendet.');
        }

        $request->session()->forget(self::TOKEN_SESSION_KEY);

        return to_route('promotion.participation.show', ['participation' => $participation->public_id]);
    }

    /** @return array{ip_address: string|null, user_agent: string|null} */
    private function accessContext(Request $request): array
    {
        return [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];
    }
}
