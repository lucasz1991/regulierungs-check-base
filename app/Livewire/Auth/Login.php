<?php

namespace App\Livewire\Auth;

use App\Services\Auth\SocialiteRuntimeConfigurator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

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

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password, 'status' => true], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Die eingegebene E-Mail-Adresse oder das Passwort ist falsch.',
            ]);
        }

        session()->regenerate();

        $this->dispatch('showAlert', 'Willkommen zurück!', 'success');

        return $this->redirectIntended('/dashboard');
    }

    public function mount()
    {
        if (request()->query('return_to') === '/gluecksrad') {
            session(['url.intended' => route('promotion.wheel')]);
        }

        // Überprüfen, ob eine Nachricht in der Session existiert
        if (session()->has('message')) {
            $this->message = session()->get('message');
            $this->messageType = session()->get('messageType', 'default');
            // Event zum Anzeigen der Nachricht dispatchen
            $this->dispatch('showAlert', $this->message, $this->messageType);
        }
    }

    public function render(SocialiteRuntimeConfigurator $socialSettings)
    {
        return view('livewire.auth.login', [
            'socialProviders' => $socialSettings->availableProviders(),
        ])->layout('layouts/app');
    }
}
