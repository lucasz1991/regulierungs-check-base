<?php

namespace App\Livewire\Auth;

use App\Http\Controllers\Participant\Promotion\RedemptionController;
use App\Services\Promotion\PromotionWinService;
use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Login extends Component
{
    public $message;
    public $messageType;
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email|max:255|exists:users,email',
        'password' => 'required|min:6|max:255',
    ];

    protected $messages = [
        'email.required' => 'Bitte gib deine E-Mail-Adresse ein.',
        'email.email' => 'Bitte gib eine gültige E-Mail-Adresse ein.',
        'email.max' => 'Die E-Mail-Adresse darf maximal 255 Zeichen lang sein.',
        'email.exists' => 'Diese E-Mail-Adresse ist nicht registriert.',
        'password.required' => 'Bitte gib dein Passwort ein.',
        'password.min' => 'Das Passwort muss mindestens 6 Zeichen lang sein.',
        'password.max' => 'Das Passwort darf maximal 255 Zeichen lang sein.',
    ];

    public function login(PromotionWinService $promotionWinService)
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'status' => true], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Die eingegebene E-Mail-Adresse oder das Passwort ist falsch.',
            ]);
        }

        session()->regenerate();

        if ($token = session()->get(RedemptionController::TOKEN_SESSION_KEY)) {
            try {
                $participation = $promotionWinService->bindToken($token, Auth::user(), [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            } catch (Throwable $exception) {
                Log::warning('Promotion-Gewinnbindung nach Login fehlgeschlagen.', [
                    'exception_class' => $exception::class,
                ]);
                session()->forget(RedemptionController::TOKEN_SESSION_KEY);

                return redirect()->route('promotion.claim')
                    ->with('promotion_error', 'Der Gewinn konnte nicht zugeordnet werden. Der Link ist möglicherweise abgelaufen oder bereits verwendet.');
            }

            session()->forget(RedemptionController::TOKEN_SESSION_KEY);

            return redirect()->route('promotion.participation.show', [
                'participation' => $participation->public_id,
            ]);
        }

        $this->dispatch('showAlert','Willkommen zurück!', 'success');
        return $this->redirectIntended('/dashboard');
    }

    public function mount()
    {
        // Überprüfen, ob eine Nachricht in der Session existiert
        if (session()->has('message')) {
            $this->message = session()->get('message');
            $this->messageType = session()->get('messageType', 'default'); 
            // Event zum Anzeigen der Nachricht dispatchen
            $this->dispatch('showAlert', $this->message, $this->messageType);
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout("layouts/app");
    }
}
