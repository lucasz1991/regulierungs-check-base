<?php

namespace Tests\Unit;

use Tests\TestCase;

class NewsPagebuilderTailwindAssetTest extends TestCase
{
    public function test_news_detail_loads_the_committed_pagebuilder_tailwind_asset(): void
    {
        $asset = public_path('adminresources/css/pagebuilder-tailwind.min.css');

        $this->assertFileExists($asset);
        $this->assertGreaterThan(100_000, filesize($asset));

        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $newsDetail = file_get_contents(
            resource_path('views/livewire/articles/news/news-show.blade.php')
        );

        $this->assertStringContainsString("@stack('pagebuilder-styles')", $layout);
        $this->assertStringContainsString("@push('pagebuilder-styles')", $newsDetail);
        $this->assertStringContainsString(
            'adminresources/css/pagebuilder-tailwind.min.css',
            $newsDetail
        );
        $this->assertStringContainsString('data-news-pagebuilder-tailwind', $newsDetail);
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
                public_path('adminresources/css/pagebuilder-tailwind.min.css')
            )
        );
    }
}
