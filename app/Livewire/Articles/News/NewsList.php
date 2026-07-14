<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
use App\Support\PublicNewsCache;
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

        if ($isAdminPreview) {
            $posts = Post::where('type', 'news')
                ->with(['newsCategory', 'pagebuilderProject'])
                ->latest('updated_at')
                ->paginate($this->perPage);
        } else {
            $page = (int) $this->getPage();
            $newsCache = app(PublicNewsCache::class);
            $generation = $newsCache->generation();

            $posts = $newsCache->remember(
                'list',
                [
                    'page' => $page,
                    'per_page' => $this->perPage,
                ],
                fn () => Post::where('type', 'news')
                    ->with(['newsCategory', 'pagebuilderProject'])
                    ->published()
                    ->latest('published_at')
                    ->paginate($this->perPage, ['*'], 'page', $page),
                $generation
            );
        }

        return view('livewire.articles.news.news-list', [
            'posts' => $posts,
            'isAdminPreview' => $isAdminPreview,
        ])->layout('layouts.app');
    }
}
