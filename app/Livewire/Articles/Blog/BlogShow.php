<?php

namespace App\Livewire\Articles\Blog;

use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;

class BlogShow extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        abort_unless(Setting::enabled('webcontent', 'blog_enabled', false), 404);
        abort_unless(
            $post->type === 'blog'
                && $post->published
                && $post->published_at
                && $post->published_at->lte(now()),
            404
        );

        $this->post = $post;
    }

    public function render()
    {
        abort_unless(Setting::enabled('webcontent', 'blog_enabled', false), 404);

        return view('livewire.articles.blog.blog-show')->layout('layouts.app');
    }
}
