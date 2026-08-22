<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class SocialProviderLogoMarkupTest extends TestCase
{
    public function test_google_and_official_apple_logos_render_as_decorative_brand_svgs(): void
    {
        $google = Blade::render('<x-social-provider-logo provider="google" />');
        $apple = Blade::render('<x-social-provider-logo provider="apple" />');

        $this->assertStringContainsString('data-social-provider-logo="google"', $google);
        $this->assertStringContainsString('fill="#4285F4"', $google);
        $this->assertStringContainsString('fill="#34A853"', $google);
        $this->assertStringContainsString('fill="#FBBC05"', $google);
        $this->assertStringContainsString('fill="#EA4335"', $google);
        $this->assertStringContainsString('data-social-provider-logo="apple"', $apple);
        $this->assertStringContainsString('viewBox="0 0 31 44"', $apple);
        $this->assertStringContainsString('fill="#000000"', $apple);

        foreach ([$google, $apple] as $markup) {
            $this->assertStringContainsString('aria-hidden="true"', $markup);
            $this->assertStringContainsString('focusable="false"', $markup);
        }
    }

    public function test_provider_buttons_use_approved_labels_and_provider_specific_styles(): void
    {
        $google = Blade::render('<x-social-provider-button provider="google" href="/google" intent="login" />');
        $apple = Blade::render('<x-social-provider-button provider="apple" href="/apple" intent="register" />');

        $this->assertStringContainsString('Mit Google anmelden', $google);
        $this->assertStringContainsString('border-[#747775]', $google);
        $this->assertStringContainsString('gap-[10px]', $google);
        $this->assertStringContainsString('px-3', $google);
        $this->assertStringContainsString('Roboto, Arial, sans-serif', $google);
        $this->assertStringContainsString('Mit Apple registrieren', $apple);
        $this->assertStringContainsString('border-black', $apple);
        $this->assertStringContainsString('h-11', $apple);
        $this->assertStringContainsString('text-[19px]', $apple);
        $this->assertStringContainsString('-apple-system', $apple);
        $this->assertFileExists(public_path('fonts/Roboto-Medium-latin.woff2'));
        $this->assertGreaterThan(20_000, filesize(public_path('fonts/Roboto-Medium-latin.woff2')));
    }

    public function test_every_active_social_auth_surface_uses_the_shared_button_component(): void
    {
        $views = [
            resource_path('views/livewire/auth/login.blade.php'),
            resource_path('views/livewire/auth/register.blade.php'),
            resource_path('views/livewire/participant/promotion/wheel-landing.blade.php'),
        ];

        foreach ($views as $view) {
            $contents = file_get_contents($view);

            $this->assertIsString($contents);
            $this->assertStringContainsString('<x-social-provider-button', $contents);
            $this->assertStringNotContainsString("? 'G' : ''", $contents);
        }
    }
}
