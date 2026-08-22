<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FaviconContractTest extends TestCase
{
    public function test_favicon_files_are_valid_square_assets_with_truthful_sizes(): void
    {
        $pngs = [
            public_path('site-images/favicon/favicon-48x48.png') => 48,
            public_path('site-images/favicon/favicon-96x96.png') => 96,
            public_path('site-images/favicon/apple-touch-icon.png') => 180,
        ];

        foreach ($pngs as $path => $expectedSize) {
            $this->assertFileExists($path);
            $dimensions = getimagesize($path);

            $this->assertIsArray($dimensions);
            $this->assertSame($expectedSize, $dimensions[0]);
            $this->assertSame($expectedSize, $dimensions[1]);
            $this->assertSame(IMAGETYPE_PNG, $dimensions[2]);
        }

        $ico = file_get_contents(public_path('favicon.ico'));

        $this->assertIsString($ico);
        $this->assertGreaterThan(54, strlen($ico));
        $this->assertSame(0, unpack('v', substr($ico, 0, 2))[1]);
        $this->assertSame(1, unpack('v', substr($ico, 2, 2))[1]);
        $this->assertSame(3, unpack('v', substr($ico, 4, 2))[1]);

        $sizes = [];
        for ($index = 0; $index < 3; $index++) {
            $sizes[] = ord($ico[6 + ($index * 16)]);
        }

        $this->assertSame([16, 32, 48], $sizes);
    }

    public function test_shared_head_markup_exposes_google_and_apple_compatible_icons(): void
    {
        $markup = Blade::render('<x-favicon-links />');

        $this->assertStringContainsString('rel="icon"', $markup);
        $this->assertStringContainsString('sizes="16x16 32x32 48x48"', $markup);
        $this->assertStringContainsString('/favicon.ico', $markup);
        $this->assertStringContainsString('sizes="96x96"', $markup);
        $this->assertStringContainsString('/site-images/favicon/favicon-96x96.png', $markup);
        $this->assertStringContainsString('rel="apple-touch-icon"', $markup);
        $this->assertStringContainsString('sizes="180x180"', $markup);
    }

    public function test_every_base_html_layout_uses_the_shared_favicon_head(): void
    {
        $layouts = [
            resource_path('views/layouts/app.blade.php'),
            resource_path('views/layouts/guest.blade.php'),
            resource_path('views/layouts/promotion.blade.php'),
            resource_path('views/components/admin-layout.blade.php'),
            resource_path('views/errors/maintenance.blade.php'),
        ];

        foreach ($layouts as $layout) {
            $contents = file_get_contents($layout);

            $this->assertIsString($contents);
            $this->assertStringContainsString('<x-favicon-links />', $contents);
            $this->assertStringNotContainsString('site-images/logo/logo-icon.png', $contents);
            $this->assertStringNotContainsString('favicon-32x32.png', $contents);
            $this->assertStringNotContainsString('favicon-16x16.png', $contents);
        }
    }
}
