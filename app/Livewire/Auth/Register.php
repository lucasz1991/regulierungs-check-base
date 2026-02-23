<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Register extends Component
{
    public $email, $password, $password_confirmation;
    public $username ,$terms;

    

    public function register()
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

        // User erstellen
        $user = User::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'current_team_id' => 4,
            'role' => 'guest', 
        ]);

        // Customer erstellen
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

        // Verifizierungs-E-Mail senden
        $user->sendEmailVerificationNotification();

        // User automatisch einloggen
        Auth::login($user);

        // Erfolgsmeldung und Weiterleitung
        $this->dispatch('showAlert', 'Registrierung erfolgreich! Bitte überprüfen Sie Ihre E-Mail, um Ihr Konto zu verifizieren.', 'success');
        session()->flash('message', 'Registrierung erfolgreich! Bitte überprüfen Sie Ihre E-Mail, um Ihr Konto zu verifizieren.');
        return redirect()->route('dashboard');
    }


    public function render()
    {
        return view('livewire.auth.register')->layout("layouts/app");
    }
}
