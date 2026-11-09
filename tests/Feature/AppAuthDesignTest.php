<?php

namespace Tests\Feature;

use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;
use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class AppAuthDesignTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->text('value')->nullable();
        });

        view()->share('errors', new ViewErrorBag);
        session()->put('_token', 'auth-design-test-token');
    }

    public function test_login_preserves_credentials_remember_and_native_actions(): void
    {
        $html = view('livewire.auth.login', ['socialProviders' => []])->render();
        $document = $this->document($html);

        $this->assertSame(1, $document->query('//h1')->length);
        $form = $document->query('//form')->item(0);
        $this->assertSame('login', $form->getAttribute('wire:submit.prevent'));
        $this->assertSame('email', $document->query('//input[@id="email"]')->item(0)->getAttribute('wire:model'));
        $this->assertSame('username', $document->query('//input[@id="email"]')->item(0)->getAttribute('autocomplete'));
        $this->assertSame('password', $document->query('//input[@id="password"]')->item(0)->getAttribute('wire:model'));
        $this->assertSame('current-password', $document->query('//input[@id="password"]')->item(0)->getAttribute('autocomplete'));
        $this->assertSame('checkbox', $document->query('//input[@id="remember_me"]')->item(0)->getAttribute('type'));
        $this->assertSame('remember', $document->query('//input[@id="remember_me"]')->item(0)->getAttribute('wire:model'));
        $this->assertSame(1, $document->query('//label[@for="remember_me"]')->length);
        $this->assertStringContainsString('aria-label="show ? \'Passwort verbergen\' : \'Passwort anzeigen\'"', $html);
        $this->assertStringContainsString('href="'.route('password.request').'"', $html);
        $this->assertStringContainsString('href="'.route('register').'"', $html);
        $this->assertStringContainsString('value="auth-design-test-token"', $html);
    }

    public function test_registration_preserves_fields_terms_and_social_destinations(): void
    {
        $html = view('livewire.auth.register', ['socialProviders' => ['google', 'apple']])->render();
        $document = $this->document($html);

        $this->assertSame(1, $document->query('//h1')->length);
        $this->assertSame('register', $document->query('//form')->item(0)->getAttribute('wire:submit.prevent'));

        foreach (['email', 'username', 'password', 'password_confirmation', 'terms'] as $field) {
            $input = $document->query('//input[@id="'.$field.'"]')->item(0);
            $this->assertNotNull($input, $field);
            $this->assertSame($field, $input->getAttribute('wire:model'));
            $this->assertSame(1, $document->query('//label[@for="'.$field.'"]')->length, $field);
        }

        $this->assertSame('checkbox', $document->query('//input[@id="terms"]')->item(0)->getAttribute('type'));
        $this->assertFalse($document->query('//input[@id="terms"]')->item(0)->hasAttribute('checked'));
        $this->assertStringContainsString('href="/termsandconditions"', $html);
        $this->assertStringContainsString('href="/privacypolicy"', $html);

        foreach (['google', 'apple'] as $provider) {
            $this->assertStringContainsString(
                htmlspecialchars(route('social.redirect', ['provider' => $provider, 'return_to' => '/dashboard']), ENT_QUOTES, 'UTF-8'),
                $html
            );
        }
    }

    public function test_password_views_keep_their_existing_actions_without_sending_mail(): void
    {
        foreach (['request-password-reset-link' => 'sendResetLink', 'reset-password' => 'resetPassword'] as $view => $action) {
            $html = view('livewire.auth.'.$view)->render();
            $document = $this->document($html);

            $this->assertSame(1, $document->query('//h1')->length);
            $this->assertSame($action, $document->query('//form')->item(0)->getAttribute('wire:submit.prevent'));
            $this->assertSame('email', $document->query('//input[@id="email"]')->item(0)->getAttribute('wire:model'));
            $this->assertStringContainsString('href="'.route('login').'"', $html);

            if ($view === 'reset-password') {
                foreach (['password', 'password_confirmation'] as $field) {
                    $input = $document->query('//input[@id="'.$field.'"]')->item(0);
                    $this->assertSame($field, $input->getAttribute('wire:model'));
                    $this->assertSame('new-password', $input->getAttribute('autocomplete'));
                }
            }
        }
    }

    public function test_guest_shell_exposes_the_shared_search_and_keeps_home_only_motion_disabled(): void
    {
        $html = view('layouts.guest', ['slot' => new HtmlString('<p>Gastinhalt</p>')])->render();
        $document = $this->document($html);
        $bodyClasses = preg_split('/\s+/', $document->query('//body')->item(0)->getAttribute('class'));

        foreach (['rc-home', 'rc-app', 'rc-auth', 'homepage-without-aos'] as $class) {
            $this->assertContains($class, $bodyClasses);
        }

        $this->assertNotContains('homepage', $bodyClasses);
        $this->assertTrue($document->query('//html')->item(0)->hasAttribute('data-app-design'));
        $this->assertFalse($document->query('//html')->item(0)->hasAttribute('data-homepage'));
        $this->assertSame(1, $document->query('//main[@id="app-content"]')->length);
        $this->assertSame(1, $document->query('//a[@href="#app-content"]')->length);
        $this->assertStringContainsString('aria-label="Suche öffnen"', $html);
        $this->assertStringContainsString('aria-label="Suche schließen"', $html);
        $this->assertStringContainsString("Alpine.store('nav'", $html);
        $this->assertSame(1, $document->query('//header[@data-homepage-header]')->length);
    }

    public function test_shared_form_controls_keep_native_submit_and_secondary_button_semantics(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-form-section submit="save">
                <x-slot name="title">Profil</x-slot>
                <x-slot name="description">Deine Angaben</x-slot>
                <x-slot name="form">
                    <x-label for="account-name" value="Name" />
                    <x-input id="account-name" wire:model="name" autocomplete="name" />
                </x-slot>
                <x-slot name="actions">
                    <x-secondary-button>Abbrechen</x-secondary-button>
                    <x-button>Speichern</x-button>
                </x-slot>
            </x-form-section>
            BLADE);
        $document = $this->document($html);

        $this->assertSame('save', $document->query('//form')->item(0)->getAttribute('wire:submit'));
        $this->assertSame('name', $document->query('//input[@id="account-name"]')->item(0)->getAttribute('wire:model'));
        $this->assertSame(1, $document->query('//label[@for="account-name"]')->length);
        $this->assertSame('button', $document->query('//button')->item(0)->getAttribute('type'));
        $this->assertSame('submit', $document->query('//button')->item(1)->getAttribute('type'));
    }

    public function test_account_panels_have_unique_reachable_targets_and_keep_csrf_logout(): void
    {
        // The page shell is under test; child component business logic remains in its own tests.
        foreach ([
            'profile.update-profile-information-form', 'profile.update-customer-information-form',
            'profile.claim-rating.confirm-claim-rating', 'profile.claim-rating.claim-rating-list',
            'profile.edit-privacy-settings', 'profile.update-password-form',
            'profile.two-factor-authentication-form', 'profile.logout-other-browser-sessions-form',
        ] as $component) {
            Livewire::component($component, AppAuthDesignPanelStub::class);
        }

        $user = new User;
        $user->forceFill(['id' => 91, 'name' => 'Design Fixture', 'email' => 'design@example.invalid']);
        $this->actingAs($user);

        $html = view('livewire.dashboard', [
            'userData' => $user,
            'promotionTickets' => collect(),
            'promotionParticipations' => collect(),
        ])->render();
        $document = $this->document($html);
        $targets = [];

        foreach ($document->query('//nav[@aria-label="Kontobereiche"]//button[@aria-controls]') as $button) {
            $target = $button->getAttribute('aria-controls');
            $this->assertSame('button', $button->getAttribute('type'));
            $this->assertNotSame('-1', $button->getAttribute('tabindex'));
            $this->assertSame(1, $document->query('//*[@id="'.$target.'"]')->length, $target);
            $targets[] = $target;
        }

        $this->assertCount(4, $targets);
        $this->assertCount(4, array_unique($targets));
        $this->assertStringContainsString('method="POST" action="'.route('logout').'"', $html);
        $this->assertStringContainsString('value="auth-design-test-token"', $html);
    }

    private function document(string $html): DOMXPath
    {
        $document = new DOMDocument;
        $document->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);

        return new DOMXPath($document);
    }
}

class AppAuthDesignPanelStub extends Component
{
    public function render(): string
    {
        return '<div>Isolierter Komponenteninhalt</div>';
    }
}
