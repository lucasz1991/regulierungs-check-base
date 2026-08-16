<?php

namespace App\Livewire\Participant\Promotion;

use App\Models\PromotionCampaign;
use App\Models\PromotionTicket;
use App\Services\Auth\CustomerAccountService;
use App\Services\Auth\SocialiteRuntimeConfigurator;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\PromotionTicketService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use RuntimeException;
use Throwable;

class WheelLanding extends Component
{
    #[Locked]
    public ?int $campaignId = null;

    #[Locked]
    public ?int $ticketId = null;

    public string $mode = 'login';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $username = '';

    public bool $remember = false;

    public bool $terms = false;

    public function mount(PromotionTicketService $tickets): void
    {
        session(['url.intended' => route('promotion.wheel')]);
        $this->refreshState($tickets);
    }

    public function login(CustomerAccountService $accounts)
    {
        $validated = $this->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['boolean'],
        ], [
            'email.required' => 'Bitte gib deine E-Mail-Adresse ein.',
            'email.email' => 'Bitte gib eine gueltige E-Mail-Adresse ein.',
            'password.required' => 'Bitte gib dein Passwort ein.',
        ]);

        $email = mb_strtolower(trim($validated['email']));
        $rateLimitKey = $this->loginRateLimitKey($email);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Zu viele Anmeldeversuche. Bitte warte noch '.RateLimiter::availableIn($rateLimitKey).' Sekunden.',
            ]);
        }

        if (! Auth::attempt([
            'email' => $email,
            'password' => $validated['password'],
            'status' => true,
            'role' => 'guest',
        ], (bool) $validated['remember'])) {
            RateLimiter::hit($rateLimitKey, 60);

            throw ValidationException::withMessages([
                'email' => 'E-Mail-Adresse oder Passwort ist nicht korrekt.',
            ]);
        }

        try {
            $accounts->assertAndNormalizeParticipant(Auth::user(), false);
        } catch (RuntimeException) {
            Auth::logout();
            RateLimiter::hit($rateLimitKey, 60);
            session()->regenerate();

            session()->flash(
                'promotion_auth_error',
                'Dieses Konto kann nicht fuer die Gluecksrad-Teilnahme verwendet werden.',
            );

            return redirect()->route('promotion.wheel');
        }

        RateLimiter::clear($rateLimitKey);
        session()->regenerate();
        $this->reset('password');

        return redirect()->route('promotion.wheel');
    }

    public function register(CustomerAccountService $accounts)
    {
        $validated = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('customers', 'username')],
            'password' => ['required', 'string', 'min:10', 'regex:/[A-Z]/', 'regex:/[\W]/', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'email.required' => 'Bitte gib deine E-Mail-Adresse ein.',
            'email.email' => 'Bitte gib eine gueltige E-Mail-Adresse ein.',
            'email.unique' => 'Zu dieser E-Mail-Adresse besteht bereits ein Konto. Bitte melde dich an.',
            'username.required' => 'Bitte waehle einen Benutzernamen.',
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'password.min' => 'Das Passwort muss mindestens 10 Zeichen lang sein.',
            'password.regex' => 'Das Passwort braucht mindestens einen Grossbuchstaben und ein Sonderzeichen.',
            'password.confirmed' => 'Die beiden Passwoerter stimmen nicht ueberein.',
            'terms.accepted' => 'Bitte stimme den Bedingungen und der Datenschutzerklaerung zu.',
        ]);

        $normalizedEmail = mb_strtolower(trim($validated['email']));
        $emailRateLimitKey = $this->registrationRateLimitKey($normalizedEmail);
        $ipRateLimitKey = $this->registrationIpRateLimitKey();
        if (RateLimiter::tooManyAttempts($emailRateLimitKey, 3)
            || RateLimiter::tooManyAttempts($ipRateLimitKey, 100)) {
            $seconds = max(
                RateLimiter::availableIn($emailRateLimitKey),
                RateLimiter::availableIn($ipRateLimitKey),
            );
            $this->addError('registration', 'Zu viele Registrierungsversuche. Bitte warte noch '.$seconds.' Sekunden.');

            return null;
        }
        RateLimiter::hit($emailRateLimitKey, 600);
        RateLimiter::hit($ipRateLimitKey, 600);

        try {
            $user = $accounts->registerPassword(
                $normalizedEmail,
                $validated['username'],
                $validated['password'],
            );
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('registration', 'Dein Konto konnte gerade nicht erstellt werden. Bitte versuche es erneut.');

            return null;
        }

        Auth::login($user);
        session()->regenerate();

        try {
            event(new Registered($user));
        } catch (Throwable $exception) {
            Log::warning('Gluecksrad-Verifikationsmail konnte nicht versendet werden.', [
                'exception_class' => $exception::class,
            ]);
        }

        $this->reset('password', 'password_confirmation');

        return redirect()->route('promotion.wheel');
    }

    public function resendVerification(): void
    {
        $user = Auth::user();
        if (! $user || $user->hasVerifiedEmail()) {
            return;
        }

        $key = 'promotion-email-verification:'.$user->getKey();
        if (! RateLimiter::attempt($key, 1, fn () => $user->sendEmailVerificationNotification(), 60)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('verification', 'Bitte warte noch '.$seconds.' Sekunden, bevor du eine weitere E-Mail anforderst.');

            return;
        }

        session()->flash('promotion_message', 'Wir haben dir einen neuen Bestaetigungslink gesendet.');
    }

    public function refreshState(PromotionTicketService $tickets): void
    {
        $user = Auth::user();
        $currentTicket = $user && $this->ticketId
            ? PromotionTicket::query()
                ->whereKey($this->ticketId)
                ->where('user_id', $user->getKey())
                ->first()
            : null;
        $campaign = $tickets->publicCampaign();

        if ($currentTicket
            && $currentTicket->status->value === 'ready'
            && (! $user->isActive() || ! $user->hasVerifiedEmail())) {
            $this->ticketId = null;
            $currentTicket = null;
        }

        if ($currentTicket && (
            $currentTicket->status->value === 'active'
            || ($currentTicket->status->value === 'completed' && ! $campaign)
        )) {
            $this->campaignId = (int) $currentTicket->campaign_id;

            return;
        }

        $nextCampaignId = $campaign?->getKey();
        if ($this->campaignId !== $nextCampaignId) {
            $this->ticketId = null;
        }
        $this->campaignId = $campaign?->getKey();

        if ($campaign && $user?->hasVerifiedEmail() && $this->ticketId === null) {
            try {
                $ticket = $tickets->ticketFor($user, $campaign)
                    ?? $tickets->ensureTicket($user, $campaign);
                $this->ticketId = (int) $ticket->getKey();
            } catch (Throwable $exception) {
                Log::warning('Gluecksrad-Ticket konnte nicht bereitgestellt werden.', [
                    'exception_class' => $exception::class,
                ]);
            }
        }
    }

    public function render(
        PromotionTicketService $tickets,
        PromotionSettingsService $promotionSettings,
        SocialiteRuntimeConfigurator $socialSettings,
    ) {
        $campaign = $this->campaignId
            ? PromotionCampaign::query()->find($this->campaignId)
            : null;
        $ticket = Auth::check() && $this->ticketId
            ? PromotionTicket::query()
                ->whereKey($this->ticketId)
                ->where('user_id', Auth::id())
                ->with([
                'participation',
                'activeTurn.results',
                'latestTurn.results',
                'effectiveResult',
                ])
                ->first()
            : null;

        return view('livewire.participant.promotion.wheel-landing', [
            'campaign' => $campaign,
            'ticket' => $ticket,
            'promotionEnabled' => $promotionSettings->isEnabled(),
            'socialProviders' => $socialSettings->availableProviders(),
        ])->layout('layouts.promotion');
    }

    private function loginRateLimitKey(string $email): string
    {
        return 'promotion-wheel-login:'.hash('sha256', $email.'|'.(request()->ip() ?? 'unknown'));
    }

    private function registrationRateLimitKey(string $email): string
    {
        return 'promotion-wheel-register-email:'.hash('sha256', $email.'|'.(request()->ip() ?? 'unknown'));
    }

    private function registrationIpRateLimitKey(): string
    {
        return 'promotion-wheel-register-ip:'.hash('sha256', request()->ip() ?? 'unknown');
    }
}
