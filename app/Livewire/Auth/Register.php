<?php

namespace App\Livewire\Auth;

use App\Http\Controllers\Participant\Promotion\RedemptionController;
use App\Models\User;
use App\Models\Customer;
use App\Models\Team;
use App\Services\Promotion\PromotionWinService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class Register extends Component
{
    public $email, $password, $password_confirmation;
    public $username ,$terms;

    

    public function register(PromotionWinService $promotionWinService)
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
                    'confirmed'
                ],
                'username' => ['required', 'string', 'max:255', Rule::unique('customers', 'username')],
                'terms' => 'required',
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

        
                'terms.required' => 'Du musst den AGBs und der Datenschutzerklärung zustimmen.',
            ]
        );

        $token = session()->get(RedemptionController::TOKEN_SESSION_KEY);

        try {
            [$user, $participation] = DB::transaction(function () use ($promotionWinService, $token): array {
                $team = Team::query()
                    ->where('name', 'Benutzer')
                    ->lockForUpdate()
                    ->firstOrFail();

                $user = User::create([
                    'name' => $this->username,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'current_team_id' => $team->getKey(),
                    'role' => 'guest',
                    'status' => true,
                ]);

                Customer::create([
                    'user_id' => $user->id,
                    'first_name' => '',
                    'last_name' => '',
                    'username' => $this->username,
                    'phone_number' => '',
                    'street' => '',
                    'city' => '',
                    'state' => '',
                    'postal_code' => '',
                    'country' => '',
                ]);

                $user->teams()->attach($team->getKey(), ['role' => 'guest']);

                $participation = $token
                    ? $promotionWinService->bindToken($token, $user, [
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    : null;

                return [$user, $participation];
            });
        } catch (Throwable $exception) {
            if ($token) {
                Log::warning('Promotion-Registrierung wurde zurückgerollt.', [
                    'exception_class' => $exception::class,
                ]);
            } else {
                report($exception);
            }
            $this->addError(
                'promotion',
                $token
                    ? 'Die Registrierung konnte nicht abgeschlossen werden, weil der Gewinn-Link nicht mehr gültig ist. Bitte scanne einen neuen QR-Code.'
                    : 'Die Registrierung konnte nicht abgeschlossen werden. Bitte versuche es erneut.',
            );

            return;
        }

        // User automatisch einloggen
        Auth::login($user);
        session()->regenerate();
        session()->forget(RedemptionController::TOKEN_SESSION_KEY);

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
        if ($participation) {
            return redirect()->route('promotion.participation.show', [
                'participation' => $participation->public_id,
            ]);
        }

        return redirect()->route('dashboard');
    }


    public function render()
    {
        return view('livewire.auth.register')->layout("layouts/app");
    }
}
