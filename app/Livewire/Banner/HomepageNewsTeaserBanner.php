<?php

namespace App\Livewire\Banner;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
use App\Support\PublicNewsCache;
use Livewire\Component;

class HomepageNewsTeaserBanner extends Component
{
    public function render()
    {
        $publicNewsEnabled = Setting::enabled('webcontent', 'news_enabled', false);
        $isAdminPreview = app(NewsPreviewAccess::class)->isActive(request());
        $newsEnabled = $publicNewsEnabled || $isAdminPreview;

        $posts = collect();

        if ($newsEnabled) {
            if ($isAdminPreview) {
                $posts = Post::where('type', 'news')
                    ->with(['newsCategory', 'pagebuilderProject'])
                    ->latest('updated_at')
                    ->limit(6)
                    ->get();
            } else {
                $newsCache = app(PublicNewsCache::class);

                $posts = $newsCache->remember(
                    'homepage-ticker',
                    ['limit' => 6],
                    fn () => Post::where('type', 'news')
                        ->with(['newsCategory', 'pagebuilderProject'])
                        ->published()
                        ->latest('published_at')
                        ->limit(6)
                        ->get()
                );
            }
        }

        return view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => $newsEnabled,
            'publicNewsEnabled' => $publicNewsEnabled,
            'isAdminPreview' => $isAdminPreview,
            'posts' => $posts,
        ]);
    }
}
