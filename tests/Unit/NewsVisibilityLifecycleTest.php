<?php

namespace Tests\Unit;

use App\Livewire\Articles\News\NewsList;
use App\Livewire\Articles\News\NewsShow;
use App\Livewire\Banner\HomepageNewsTeaserBanner;
use App\Models\Post;
use App\Support\NewsPreviewAccess;
use App\Support\PublicNewsCache;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class NewsVisibilityLifecycleTest extends TestCase
{
    private const CONNECTION = 'news_visibility_testing';

    private Request $request;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-07-14 12:00:00');

        config([
            'database.default' => self::CONNECTION,
            'database.connections.'.self::CONNECTION => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        DB::purge(self::CONNECTION);

        Schema::connection(self::CONNECTION)->create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->longText('value')->nullable();
            $table->timestamps();
        });

        Schema::connection(self::CONNECTION)->create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('news_category_id')->nullable();
            $table->unsignedBigInteger('pagebuilder_project_id')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('layout')->default('image_top');
            $table->json('images')->nullable();
            $table->timestamps();
        });

        $this->request = Request::create('/news/current-news', 'GET');
        $this->app->instance('request', $this->request);
        Cache::flush();
        $this->setPreviewActive(false);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::purge(self::CONNECTION);

        parent::tearDown();
    }

    public function test_expired_preview_is_rejected_on_the_next_render(): void
    {
        $this->setNewsEnabled(false);
        $draft = $this->createNews('preview-draft', false, null);
        $this->setPreviewActive(true);

        $component = new NewsShow;
        $component->mount($draft);

        $this->assertFalse(property_exists($component, 'isAdminPreview'));

        $this->setPreviewActive(false);

        $this->expectException(NotFoundHttpException::class);
        $component->render();
    }

    public function test_disabling_news_is_enforced_on_the_next_render(): void
    {
        $this->setNewsEnabled(true);
        $post = $this->createNews('public-news', true, now()->subMinute());

        $component = new NewsShow;
        $component->mount($post);

        $this->setNewsEnabled(false);

        $this->expectException(NotFoundHttpException::class);
        $component->render();
    }

    public function test_scheduled_news_detail_is_not_publicly_accessible(): void
    {
        $this->setNewsEnabled(true);
        $scheduled = $this->createNews('scheduled-news', true, now()->addMinute());

        $this->expectException(NotFoundHttpException::class);

        (new NewsShow)->mount($scheduled);
    }

    public function test_public_list_related_and_ticker_exclude_scheduled_news(): void
    {
        $this->setNewsEnabled(true);

        $current = $this->createNews('current-news', true, now()->subMinutes(10));
        $visible = $this->createNews('visible-related-news', true, now()->subMinutes(5));
        $this->createNews('scheduled-related-news', true, now()->addMinutes(5));
        $this->createNews('draft-related-news', false, now()->subMinute());

        $detail = new NewsShow;
        $detail->mount($current);
        $relatedIds = $detail->render()->getData()['relatedPosts']->pluck('id')->all();

        $listIds = (new NewsList)->render()
            ->getData()['posts']
            ->getCollection()
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $tickerIds = (new HomepageNewsTeaserBanner)->render()
            ->getData()['posts']
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $publishedIds = Post::where('type', 'news')
            ->published()
            ->pluck('id')
            ->sort()
            ->values()
            ->all();

        $expectedPublicIds = collect([$current->id, $visible->id])->sort()->values()->all();

        $this->assertSame([$visible->id], $relatedIds);
        $this->assertSame($expectedPublicIds, $listIds);
        $this->assertSame($expectedPublicIds, $tickerIds);
        $this->assertSame($expectedPublicIds, $publishedIds);
    }

    public function test_public_news_surfaces_refresh_when_the_shared_generation_changes(): void
    {
        $this->setNewsEnabled(true);
        $this->setNewsCacheGeneration('generation-a');

        $current = $this->createNews('cache-current', true, now()->subMinutes(10));
        $firstRelated = $this->createNews('cache-first-related', true, now()->subMinutes(5));

        $list = new NewsList;
        $ticker = new HomepageNewsTeaserBanner;
        $detail = new NewsShow;
        $detail->mount($current);

        $this->assertSame(
            [$current->id, $firstRelated->id],
            $list->render()->getData()['posts']->getCollection()->pluck('id')->sort()->values()->all()
        );
        $this->assertSame(
            [$current->id, $firstRelated->id],
            $ticker->render()->getData()['posts']->pluck('id')->sort()->values()->all()
        );
        $this->assertSame(
            [$firstRelated->id],
            $detail->render()->getData()['relatedPosts']->pluck('id')->all()
        );

        $newRelated = $this->createNews('cache-new-related', true, now()->subMinute());

        $this->assertNotContains(
            $newRelated->id,
            $list->render()->getData()['posts']->getCollection()->pluck('id')->all()
        );
        $this->assertNotContains(
            $newRelated->id,
            $ticker->render()->getData()['posts']->pluck('id')->all()
        );
        $this->assertNotContains(
            $newRelated->id,
            $detail->render()->getData()['relatedPosts']->pluck('id')->all()
        );

        $this->setNewsCacheGeneration('generation-b');

        $this->assertContains(
            $newRelated->id,
            $list->render()->getData()['posts']->getCollection()->pluck('id')->all()
        );
        $this->assertContains(
            $newRelated->id,
            $ticker->render()->getData()['posts']->pluck('id')->all()
        );
        $this->assertContains(
            $newRelated->id,
            $detail->render()->getData()['relatedPosts']->pluck('id')->all()
        );
    }

    public function test_admin_preview_news_surfaces_bypass_the_public_cache(): void
    {
        $this->setNewsEnabled(true);
        $this->setNewsCacheGeneration('preview-bypass');

        $current = $this->createNews('preview-cache-current', true, now()->subMinutes(10));

        (new NewsList)->render();
        (new HomepageNewsTeaserBanner)->render();

        $this->setPreviewActive(true);
        $draft = $this->createNews('preview-cache-draft', false, null);

        $this->assertContains(
            $draft->id,
            (new NewsList)->render()->getData()['posts']->getCollection()->pluck('id')->all()
        );
        $this->assertContains(
            $draft->id,
            (new HomepageNewsTeaserBanner)->render()->getData()['posts']->pluck('id')->all()
        );

        $detail = new NewsShow;
        $detail->mount($current);
        $detail->render();

        $newDraft = $this->createNews('preview-cache-new-draft', false, null);

        $this->assertContains(
            $newDraft->id,
            $detail->render()->getData()['relatedPosts']->pluck('id')->all()
        );
    }

    public function test_public_news_cache_keys_separate_locale_page_and_generation_and_expire_after_sixty_seconds(): void
    {
        $this->setNewsCacheGeneration('key-generation-a');
        $newsCache = app(PublicNewsCache::class);

        app()->setLocale('de');
        $germanPageOne = $newsCache->key('list', ['page' => 1, 'per_page' => 6]);
        $germanPageTwo = $newsCache->key('list', ['page' => 2, 'per_page' => 6]);

        app()->setLocale('en');
        $englishPageOne = $newsCache->key('list', ['page' => 1, 'per_page' => 6]);

        $this->setNewsCacheGeneration('key-generation-b');
        $newGenerationPageOne = $newsCache->key('list', ['page' => 1, 'per_page' => 6]);

        $this->assertNotSame($germanPageOne, $germanPageTwo);
        $this->assertNotSame($germanPageOne, $englishPageOne);
        $this->assertNotSame($englishPageOne, $newGenerationPageOne);

        $calls = 0;
        $resolver = function () use (&$calls): int {
            return ++$calls;
        };

        $this->assertSame(1, $newsCache->remember('ttl', ['post_id' => 1], $resolver));
        $this->assertSame(1, $newsCache->remember('ttl', ['post_id' => 1], $resolver));

        Carbon::setTestNow(now()->addSeconds(PublicNewsCache::TTL_SECONDS + 1));

        $this->assertSame(2, $newsCache->remember('ttl', ['post_id' => 1], $resolver));
    }

    public function test_public_news_cache_reads_the_latest_legacy_generation_row(): void
    {
        DB::table('settings')->insert([
            [
                'type' => 'webcontent',
                'key' => 'news_cache_version',
                'value' => 'legacy-first',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'webcontent',
                'key' => 'news_cache_version',
                'value' => 'legacy-latest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->assertSame(
            'legacy-latest',
            app(PublicNewsCache::class)->generation()
        );
    }

    private function setPreviewActive(bool $active): void
    {
        $this->request->attributes->set(NewsPreviewAccess::REQUEST_ATTRIBUTE, $active);
    }

    private function setNewsEnabled(bool $enabled): void
    {
        DB::table('settings')->updateOrInsert(
            ['type' => 'webcontent', 'key' => 'news_enabled'],
            [
                'value' => $enabled ? '1' : '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function setNewsCacheGeneration(string $generation): void
    {
        DB::table('settings')->updateOrInsert(
            ['type' => 'webcontent', 'key' => 'news_cache_version'],
            [
                'value' => $generation,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function createNews(string $slug, bool $published, ?Carbon $publishedAt): Post
    {
        return Post::create([
            'type' => 'news',
            'title' => str_replace('-', ' ', ucfirst($slug)),
            'slug' => $slug,
            'published' => $published,
            'published_at' => $publishedAt,
            'body' => '<p>News body</p>',
        ]);
    }
}
