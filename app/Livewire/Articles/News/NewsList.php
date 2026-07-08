<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class NewsList extends Component
{
    use WithPagination;

    public int $perPage = 6;

    public function loadMore(): void
    {
        $this->perPage += 6;
    }

    public function render()
    {
        abort_unless(Setting::enabled('webcontent', 'news_enabled', false), 404);

        $posts = Post::where('type', 'news')
            ->published()
            ->latest('published_at')
            ->paginate($this->perPage);

        return view('livewire.articles.news.news-list', [
            'posts' => $posts,
        ])->layout('layouts.app');
    }
}
