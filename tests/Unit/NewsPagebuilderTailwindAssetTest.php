<?php

namespace Tests\Unit;

use Tests\TestCase;

class NewsPagebuilderTailwindAssetTest extends TestCase
{
    public function test_news_detail_does_not_load_admin_tailwind_globally(): void
    {
        $asset = public_path('adminresources/css/tailwind.min.css');

        $this->assertFileExists($asset);
        $this->assertGreaterThan(100_000, filesize($asset));

        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $newsComponent = file_get_contents(
            app_path('Livewire/Articles/News/NewsShow.php')
        );

        $this->assertStringContainsString(
            '$loadNewsPagebuilderTailwind ?? false',
            $layout
        );
        $this->assertStringContainsString(
            'adminresources/css/tailwind.min.css',
            $layout
        );
        $this->assertStringContainsString('is_file($newsTailwindPath)', $layout);
        $this->assertStringContainsString('data-news-pagebuilder-tailwind', $layout);
        $this->assertStringNotContainsString('loadNewsPagebuilderTailwind', $newsComponent);
        $this->assertStringContainsString("->layout('layouts.app')", $newsComponent);
    }

    public function test_committed_asset_matches_the_admin_build_when_both_projects_exist(): void
    {
        $adminAsset = base_path(
            '../regulierungs-check-admin/public/build/css/tailwind.min.css'
        );

        if (! is_file($adminAsset)) {
            $this->markTestSkipped('Das lokale Admin-Build-Asset ist nicht vorhanden.');
        }

        $this->assertSame(
            hash_file('sha256', $adminAsset),
            hash_file(
                'sha256',
                public_path('adminresources/css/tailwind.min.css')
            )
        );
    }
}
