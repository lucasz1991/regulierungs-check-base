<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageAosTest extends TestCase
{
    public function test_homepage_does_not_load_or_initialize_the_legacy_aos_script(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('homepage-without-aos', false)
            ->assertSee('adminresources/aos/aos.css', false)
            ->assertDontSee('adminresources/aos/aos.js', false)
            ->assertDontSee('AOS.init(', false);
    }

    public function test_other_public_pages_keep_aos_available(): void
    {
        $response = $this->get(route('howto'));

        $response
            ->assertOk()
            ->assertDontSee('homepage-without-aos', false)
            ->assertSee('adminresources/aos/aos.css', false)
            ->assertDontSee('adminresources/aos/aos.js', false)
            ->assertDontSee('AOS.init(', false);
    }

    public function test_bundled_aos_runtime_skips_home_and_refreshes_other_livewire_pages(): void
    {
        $app = file_get_contents(resource_path('js/app.js'));
        $script = file_get_contents(resource_path('js/aos.js'));

        $this->assertStringContainsString("import './aos';", $app);
        $this->assertStringContainsString("import AOS from 'aos';", $script);
        $this->assertStringContainsString("classList.contains('homepage-without-aos')", $script);
        $this->assertStringContainsString('if (isHomepage)', $script);
        $this->assertStringContainsString('AOS.init()', $script);
        $this->assertStringContainsString('AOS.refreshHard()', $script);
        $this->assertStringContainsString(
            "document.addEventListener('livewire:navigated', synchronizeAos)",
            $script
        );
    }

    public function test_homepage_override_keeps_dynamic_aos_content_visible_without_animation(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertMatchesRegularExpression(
            '/body\.homepage-without-aos\s+\[data-aos\],[^{]*body\.homepage-without-aos\s+\.aos-init,[^{]*body\.homepage-without-aos\s+\.aos-animate\s*\{[^}]*opacity:\s*1\s*!important;[^}]*transform:\s*none\s*!important;[^}]*transition:\s*none\s*!important;[^}]*animation:\s*none\s*!important;[^}]*pointer-events:\s*auto\s*!important;/s',
            $css
        );
    }
}
