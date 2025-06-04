<?php

namespace App\Livewire\Articles\Blog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class BlogList extends Component
{
    use WithPagination;

    public $perPage = 6;
    public $selectedCategory = null;
    public $categories = [];

    protected $queryString = ['selectedCategory'];

    public function mount()
    {
        
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
        $query = Post::where('type', 'blog')
            ->published()
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->latest('published_at');

        return view('livewire.articles.blog.blog-list', [
            'posts' => $query->paginate($this->perPage),
        ])->layout('layouts.app');
    }
}
