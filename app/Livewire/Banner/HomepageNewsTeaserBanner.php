<?php

namespace App\Livewire\Banner;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
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
            $postsQuery = Post::where('type', 'news')
                ->with(['newsCategory', 'pagebuilderProject']);

            if ($isAdminPreview) {
                $postsQuery->latest('updated_at');
            } else {
                $postsQuery->published()->latest('published_at');
            }

            $posts = $postsQuery->limit(6)->get();
        }

        $tickerItems = collect();
        $tickerShouldAnimate = $posts->count() > 1;

        if ($posts->isNotEmpty()) {
            $tickerItemCount = $tickerShouldAnimate ? max(6, $posts->count()) : 1;

            for ($index = 0; $index < $tickerItemCount; $index++) {
                $tickerItems->push([
                    'post' => $posts[$index % $posts->count()],
                    'is_filler' => $index >= $posts->count(),
                ]);
            }
        }

        return view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => $newsEnabled,
            'publicNewsEnabled' => $publicNewsEnabled,
            'isAdminPreview' => $isAdminPreview,
            'posts' => $posts,
            'tickerItems' => $tickerItems,
            'tickerShouldAnimate' => $tickerShouldAnimate,
        ]);
    }
}
