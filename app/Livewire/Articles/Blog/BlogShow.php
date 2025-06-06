<?php

namespace App\Livewire\Articles\Blog;

use Livewire\Component;
use App\Models\Post;

class BlogShow extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.articles.blog.blog-show')->layout('layouts.app');
    }
}
