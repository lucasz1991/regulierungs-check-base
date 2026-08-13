<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class InsuranceTypeIconRenderSecurityTest extends TestCase
{
    public function test_public_rating_form_raw_icon_path_fails_closed_for_legacy_payload(): void
    {
        $type = (object) [
            'id' => 1,
            'name' => 'Legacy',
            'icon_type' => 'svg',
            'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(2)</script></svg>',
        ];

        $html = Blade::render(<<<'BLADE'
@php($safeIcon = \App\Support\SafeIconMarkup::forType($type->icon_type, $type->icon_svg))
@if($safeIcon)
    @if($type->icon_type === 'svg')
        {!! $safeIcon !!}
    @elseif($type->icon_type === 'fontawesome')
        <i class="{{ $safeIcon }}"></i>
    @endif
@endif
BLADE, compact('type'));

        $this->assertSame('', trim($html));
        $this->assertStringNotContainsString('onload', $html);
        $this->assertStringNotContainsString('<script', $html);
    }

    public function test_public_banner_raw_icon_path_renders_only_allowlisted_markup(): void
    {
        $type = (object) [
            'id' => 2,
            'name' => 'Sicher',
            'icon_type' => 'svg',
            'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M2 2 L22 22 Z"/></svg>',
        ];

        $html = Blade::render(<<<'BLADE'
@php($safeIcon = \App\Support\SafeIconMarkup::forType($type->icon_type, $type->icon_svg))
@if($safeIcon)
    @if($type->icon_type === 'svg')
        {!! $safeIcon !!}
    @elseif($type->icon_type === 'fontawesome')
        <i class="{{ $safeIcon }}"></i>
    @endif
@endif
BLADE, compact('type'));

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('<path', $html);
        $this->assertStringNotContainsString('&lt;svg', $html);
    }

    public function test_public_fontawesome_raw_icon_path_rejects_attribute_injection(): void
    {
        $type = (object) [
            'id' => 3,
            'name' => 'Legacy Font',
            'icon_type' => 'fontawesome',
            'icon_svg' => 'fas fa-shield-alt" autofocus onfocus="alert(1)',
        ];

        $html = Blade::render(<<<'BLADE'
@php($safeIcon = \App\Support\SafeIconMarkup::forType($type->icon_type, $type->icon_svg))
@if($safeIcon)
    @if($type->icon_type === 'svg')
        {!! $safeIcon !!}
    @elseif($type->icon_type === 'fontawesome')
        <i class="{{ $safeIcon }}"></i>
    @endif
@endif
BLADE, compact('type'));

        $this->assertSame('', trim($html));
        $this->assertStringNotContainsString('autofocus', $html);
        $this->assertStringNotContainsString('onfocus', $html);
    }

    public function test_public_icon_templates_do_not_render_database_markup_directly(): void
    {
        $paths = [
            resource_path('views/livewire/customer/rating/rating-form.blade.php'),
            resource_path('views/livewire/banner/top-insurances-by-type-banner.blade.php'),
        ];

        foreach ($paths as $path) {
            $source = file_get_contents($path);

            $this->assertIsString($source);
            $this->assertStringContainsString('SafeIconMarkup::forType', $source);
            $this->assertDoesNotMatchRegularExpression('/\{!!\s*\$type->icon_svg\s*!!\}/', $source);
            $this->assertDoesNotMatchRegularExpression('/class="\{!!\s*\$type->icon_svg\s*!!\}"/', $source);
        }
    }
}

