<?php

namespace App\Livewire\Auth;

use App\Services\Auth\CustomerAccountService;
use App\Services\Auth\SocialiteRuntimeConfigurator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class Register extends Component
{
    public $email;

    public $password;

    public $password_confirmation;

    public $username;

    public $terms;

    public function register(CustomerAccountService $accounts)
    {
        // Validierung
        $this->validate(
            [
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'min:10',
                    'regex:/[A-Z]/',
                    'regex:/[\W]/',
                    'confirmed',
                ],
                'username' => ['required', 'string', 'max:255', Rule::unique('customers', 'username')],
                'terms' => 'accepted',
            ],
            [
                'email.required' => 'Die E-Mail-Adresse ist erforderlich.',
                'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
                'email.unique' => 'Diese E-Mail-Adresse wird bereits verwendet.',

                'password.required' => 'Das Passwort ist erforderlich.',
                'password.min' => 'Das Passwort muss mindestens 10 Zeichen lang sein.',
                'password.regex' => 'Das Passwort muss mindestens einen Großbuchstaben und ein Sonderzeichen enthalten.',
                'password.confirmed' => 'Die Passwort-Bestätigung stimmt nicht überein.',

                'username.required' => 'Der Benutzername ist erforderlich.',
                'username.string' => 'Der Benutzername muss eine Zeichenkette sein.',
                'username.max' => 'Der Benutzername darf maximal 255 Zeichen lang sein.',
                'username.unique' => 'Dieser Benutzername wird bereits verwendet.',

                'terms.accepted' => 'Du musst den AGBs und der Datenschutzerklärung zustimmen.',
            ]
        );

        try {
            $user = $accounts->registerPassword($this->email, $this->username, $this->password);
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('registration', 'Die Registrierung konnte nicht abgeschlossen werden. Bitte versuche es erneut.');

            return;
        }

        // User automatisch einloggen
        Auth::login($user);
        session()->regenerate();

        // Die Kontenerstellung bleibt erfolgreich, auch wenn der Mailtransport ausfällt.
        $verificationNotificationSent = true;
        try {
            event(new Registered($user));
            $verificationNotificationSent = ! session()->has('error');
        } catch (Throwable $exception) {
            report($exception);
            $verificationNotificationSent = false;
        }

        if ($verificationNotificationSent) {
            $message = 'Registrierung erfolgreich! Bitte überprüfe deine E-Mail, um dein Konto zu verifizieren.';
            $messageType = 'success';
        } else {
            $message = 'Dein Konto wurde erstellt. Die Bestätigungs-E-Mail konnte gerade nicht versendet werden; du kannst sie im nächsten Schritt erneut anfordern.';
            $messageType = 'warning';
        }

        // The session is regenerated above. Dispatching a Livewire browser
        // event here would make the still-mounted alert child send another
        // request with the old CSRF token before the redirect and show a 419
        // despite a successful, committed registration.
        session()->flash('message', $message);
        session()->flash('messageType', $messageType);

        return redirect()->intended(route('dashboard'));
    }

    public function mount(): void
    {
        if (request()->query('return_to') === '/gluecksrad') {
            session(['url.intended' => route('promotion.wheel')]);
        }
    }

    public function render(SocialiteRuntimeConfigurator $socialSettings)
    {
        return view('livewire.auth.register', [
            'socialProviders' => $socialSettings->availableProviders(),
        ])->layout('layouts/app');
    }
}
