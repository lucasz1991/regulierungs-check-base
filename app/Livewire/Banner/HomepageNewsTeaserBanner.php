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

        $tickerItems = $posts
            ->values()
            ->map(fn (Post $post, int $index) => [
                'post' => $post,
                'is_filler' => false,
                'slot' => $index + 1,
            ]);

        $tickerShouldAnimate = $posts->isNotEmpty();

        // Responsive empty cards fill the viewport without turning repeated News
        // into additional links or screen-reader content. The duplicated sequence
        // still brings a single real item back in seamlessly from the right.
        if ($posts->isNotEmpty()) {
            for ($slot = $posts->count() + 1; $slot <= 12; $slot++) {
                $tickerItems->push([
                    'post' => null,
                    'is_filler' => true,
                    'slot' => $slot,
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
