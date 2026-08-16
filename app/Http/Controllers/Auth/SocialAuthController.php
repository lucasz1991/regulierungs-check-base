<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\CustomerAccountService;
use App\Services\Auth\SocialiteRuntimeConfigurator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use RuntimeException;
use Throwable;

final class SocialAuthController extends Controller
{
    public function redirect(string $provider, SocialiteRuntimeConfigurator $settings): RedirectResponse
    {
        try {
            $settings->configure($provider);
        } catch (RuntimeException $exception) {
            return redirect()->route('login')->withErrors(['social' => $exception->getMessage()]);
        }

        session(['social_auth_return_to' => $this->safeReturnPath((string) request()->query('return_to'))]);

        // Apple posts the authorization response back from another site. A
        // Lax session cookie is not sent with that POST, which would discard
        // Socialite's state value. Only the Apple redirect response upgrades
        // the existing session cookie; the callback response returns to the
        // normal Lax policy automatically.
        if ($provider === 'apple') {
            config()->set('session.same_site', 'none');
            config()->set('session.secure', true);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(
        string $provider,
        SocialiteRuntimeConfigurator $settings,
        CustomerAccountService $accounts,
    ): RedirectResponse {
        try {
            $settings->configure($provider);
            $socialUser = Socialite::driver($provider)->user();
            $email = Str::lower(trim((string) $socialUser->getEmail()));
            $providerId = trim((string) $socialUser->getId());

            if ($email === '' || $providerId === '' || ! $this->providerEmailIsVerified($provider, (array) $socialUser->user)) {
                throw new RuntimeException('Der Anbieter hat keine bestaetigte E-Mail-Adresse uebermittelt.');
            }

            $user = $accounts->loginOrRegisterSocial(
                $provider,
                $providerId,
                $email,
                trim((string) $socialUser->getName()),
            );

            Auth::login($user, true);
            request()->session()->regenerate();

            $returnTo = $this->safeReturnPath((string) session()->pull('social_auth_return_to', '/dashboard'));

            return redirect()->to($returnTo);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('login')->withErrors([
                'social' => $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'Die Anmeldung konnte nicht abgeschlossen werden. Bitte versuche es erneut.',
            ]);
        }
    }

    private function providerEmailIsVerified(string $provider, array $payload): bool
    {
        $value = $payload['email_verified'] ?? $payload['verified_email'] ?? false;

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    private function safeReturnPath(string $path): string
    {
        return $path === '/gluecksrad' ? '/gluecksrad' : '/dashboard';
    }
}
