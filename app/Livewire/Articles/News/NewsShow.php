<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;

class NewsShow extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless(
            Setting::enabled('webcontent', 'news_enabled', false)
                && $post->type === 'news'
                && $post->published
                && $post->published_at !== null,
            404
        );

        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.articles.news.news-show')->layout('layouts.app');
    }
}
