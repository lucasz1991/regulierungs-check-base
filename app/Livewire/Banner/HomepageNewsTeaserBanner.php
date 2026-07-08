<?php

namespace App\Livewire\Banner;

use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;

class HomepageNewsTeaserBanner extends Component
{
    public function render()
    {
        $newsEnabled = Setting::enabled('webcontent', 'news_enabled', false);

        $posts = $newsEnabled
            ? Post::where('type', 'news')
                ->published()
                ->latest('published_at')
                ->limit(3)
                ->get()
            : collect();

        return view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => $newsEnabled,
            'posts' => $posts,
        ]);
    }
}
