<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
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
        $publicNewsEnabled = Setting::enabled('webcontent', 'news_enabled', false);
        $isAdminPreview = app(NewsPreviewAccess::class)->isActive(request());

        abort_unless($publicNewsEnabled || $isAdminPreview, 404);

        $postsQuery = Post::where('type', 'news')
            ->with(['newsCategory', 'pagebuilderProject']);

        if ($isAdminPreview) {
            $postsQuery->latest('updated_at');
        } else {
            $postsQuery->published()->latest('published_at');
        }

        $posts = $postsQuery->paginate($this->perPage);

        return view('livewire.articles.news.news-list', [
            'posts' => $posts,
            'isAdminPreview' => $isAdminPreview,
        ])->layout('layouts.app');
    }
}
