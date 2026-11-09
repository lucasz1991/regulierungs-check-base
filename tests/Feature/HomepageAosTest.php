<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomepageAosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        // The shared layout resolves optional CMS heading settings before its tags.
        Schema::create('web_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug');
            $table->string('title')->nullable();
            $table->text('icon')->nullable();
            $table->string('header_image')->nullable();
            $table->json('settings')->nullable();
        });
    }

    public function test_homepage_shell_disables_legacy_aos_and_keeps_its_homepage_marker(): void
    {
        $shell = $this->renderAppShellMarkers('home');

        $this->assertStringContainsString('data-app-design', $shell);
        $this->assertMatchesRegularExpression('/<html\b[^>]*\sdata-homepage(?:\s|>)/', $shell);
        $this->assertStringContainsString('homepage-without-aos rc-home homepage', $shell);
        $this->assertStringNotContainsString('rc-app', $shell);
    }

    public function test_other_app_pages_share_the_new_shell_and_skip_legacy_aos(): void
    {
        foreach (['howto', 'contact', 'insurances', 'news.index'] as $route) {
            $shell = $this->renderAppShellMarkers($route);

            $this->assertStringContainsString('data-app-design', $shell, $route);
            $this->assertStringContainsString('homepage-without-aos rc-home rc-app', $shell, $route);
            $this->assertDoesNotMatchRegularExpression('/<html\b[^>]*\sdata-homepage(?:\s|>)/', $shell, $route);
        }
    }

    public function test_themed_app_layout_does_not_load_legacy_aos_assets_or_initialize_it_inline(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringNotContainsString('adminresources/aos/aos.css', $layout);
        $this->assertStringNotContainsString('adminresources/aos/aos.js', $layout);
        $this->assertStringNotContainsString('AOS.init(', $layout);
    }

    public function test_bundled_aos_runtime_skips_themed_pages_and_preserves_non_themed_livewire_support(): void
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

    public function test_shared_override_keeps_dynamic_aos_content_visible_without_animation(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertMatchesRegularExpression(
            '/body\.homepage-without-aos\s+\[data-aos\],[^{]*body\.homepage-without-aos\s+\.aos-init,[^{]*body\.homepage-without-aos\s+\.aos-animate\s*\{[^}]*opacity:\s*1\s*!important;[^}]*transform:\s*none\s*!important;[^}]*transition:\s*none\s*!important;[^}]*animation:\s*none\s*!important;[^}]*pointer-events:\s*auto\s*!important;/s',
            $css
        );
    }

    private function renderAppShellMarkers(string $routeName): string
    {
        // Render the actual Blade branches without unrelated business-data fixtures.
        // Full-page route and interaction coverage belongs to the application UI tests.
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $doctype = strpos($layout, '<!DOCTYPE');
        $this->assertNotFalse($doctype);
        $this->assertSame(1, preg_match('~<html\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>~s', $layout, $html));
        $this->assertSame(1, preg_match('~<body\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>~s', $layout, $body));

        $route = Route::getRoutes()->getByName($routeName);
        $this->assertNotNull($route);
        $request = Request::create('/'.ltrim($route->uri(), '/'), 'GET');
        $request->setRouteResolver(fn () => $route);
        $originalRequest = $this->app->make('request');
        $this->app->instance('request', $request);

        try {
            return Blade::render(substr($layout, 0, $doctype).$html[0].$body[0]);
        } finally {
            $this->app->instance('request', $originalRequest);
        }
    }
}
