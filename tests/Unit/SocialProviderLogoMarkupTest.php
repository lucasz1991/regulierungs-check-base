<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class SocialProviderLogoMarkupTest extends TestCase
{
    public function test_google_and_apple_logos_render_as_decorative_brand_svgs(): void
    {
        $google = Blade::render('<x-social-provider-logo provider="google" />');
        $apple = Blade::render('<x-social-provider-logo provider="apple" />');

        $this->assertStringContainsString('data-social-provider-logo="google"', $google);
        $this->assertStringContainsString('fill="#4285F4"', $google);
        $this->assertStringContainsString('fill="#34A853"', $google);
        $this->assertStringContainsString('fill="#FBBC05"', $google);
        $this->assertStringContainsString('fill="#EA4335"', $google);
        $this->assertStringContainsString('data-social-provider-logo="apple"', $apple);

        foreach ([$google, $apple] as $markup) {
            $this->assertStringContainsString('aria-hidden="true"', $markup);
            $this->assertStringContainsString('focusable="false"', $markup);
        }
    }

    public function test_every_active_social_auth_surface_uses_the_shared_logo_component(): void
    {
        $views = [
            resource_path('views/livewire/auth/login.blade.php'),
            resource_path('views/livewire/auth/register.blade.php'),
            resource_path('views/livewire/participant/promotion/wheel-landing.blade.php'),
        ];

        foreach ($views as $view) {
            $contents = file_get_contents($view);

            $this->assertIsString($contents);
            $this->assertStringContainsString('<x-social-provider-logo :provider="$provider" />', $contents);
            $this->assertStringNotContainsString("? 'G' : ''", $contents);
        }
    }
}
