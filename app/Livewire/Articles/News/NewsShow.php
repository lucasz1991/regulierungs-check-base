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

        $this->post = $post->load(['newsCategory', 'pagebuilderProject']);
    }

    public function render()
    {
        $relatedPosts = Post::where('type', 'news')
            ->published()
            ->where('id', '!=', $this->post->id)
            ->with('newsCategory')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('livewire.articles.news.news-show', [
            'relatedPosts' => $relatedPosts,
        ])->layout('layouts.app');
    }
}
