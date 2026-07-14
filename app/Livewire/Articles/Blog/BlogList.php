<?php

namespace App\Livewire\Articles\Blog;

use App\Models\BlogCategory;
use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class BlogList extends Component
{
    use WithPagination;

    public $perPage = 6;

    public $selectedCategory = null;

    public $categories = [];

    protected $queryString = ['selectedCategory'];

    public function mount(): void
    {
        abort_unless(Setting::enabled('webcontent', 'blog_enabled', false), 404);

        $this->categories = BlogCategory::all();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 6;
    }

    public function render()
    {
        abort_unless(Setting::enabled('webcontent', 'blog_enabled', false), 404);

        $query = Post::where('type', 'blog')
            ->published()
            ->where('published_at', '<=', now())
            ->when($this->selectedCategory, fn ($q) => $q->where('category_id', $this->selectedCategory))
            ->latest('published_at');

        return view('livewire.articles.blog.blog-list', [
            'posts' => $query->paginate($this->perPage),
        ])->layout('layouts.app');
    }
}
