<?php

namespace Tests\Unit;

use App\View\Components\PageHeader;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WebPageHeaderSecurityTest extends TestCase
{
    public function test_public_page_header_sanitizes_legacy_icons_and_escapes_titles(): void
    {
        $component = file_get_contents(app_path('View/Components/PageHeader.php'));
        $view = file_get_contents(resource_path('views/components/page-header.blade.php'));

        $this->assertStringContainsString('SafeIconMarkup::svg($webPage->icon)', $component);
        $this->assertStringContainsString('{{ $title }}', $view);
        $this->assertStringContainsString('{!! $safeIcon !!}', $view);
        $this->assertStringNotContainsString('{!! $title !!}', $view);
        $this->assertStringNotContainsString('{!! $icon !!}', $view);
    }

    public function test_public_page_header_fail_closed_renders_legacy_database_values(): void
    {
        Schema::create('web_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('icon')->nullable();
            $table->string('header_image')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        $title = '"><img src=x onerror=alert(1)>';
        $activeIcon = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(2)"><script>alert(3)</script></svg>';
        $safeIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M0 0L10 10"/></svg>';

        $pageId = DB::table('web_pages')->insertGetId([
            'title' => $title,
            'slug' => 'legacy-page',
            'icon' => $activeIcon,
            'settings' => json_encode(['showHeader' => true], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        app()->instance('request', Request::create('/legacy-page', 'GET'));

        $unsafe = new PageHeader;
        $unsafeHtml = $this->renderHeader($unsafe);

        $this->assertStringContainsString(e($title), $unsafeHtml);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $unsafeHtml);
        $this->assertStringNotContainsString('<script', $unsafeHtml);
        $this->assertStringNotContainsString('onload="alert(2)"', $unsafeHtml);
        $this->assertNull($unsafe->safeIcon);

        DB::table('web_pages')->where('id', $pageId)->update(['icon' => $safeIcon]);

        $safe = new PageHeader;
        $safeHtml = $this->renderHeader($safe);

        $this->assertStringContainsString('M0 0L10 10', $safeHtml);
        $this->assertStringNotContainsString('onload', (string) $safe->safeIcon);
    }

    private function renderHeader(PageHeader $component): string
    {
        return view('components.page-header', [
            'isWebPage' => $component->isWebPage,
            'showHeader' => $component->showHeader,
            'title' => $component->title,
            'safeIcon' => $component->safeIcon,
        ])->render();
    }
}
