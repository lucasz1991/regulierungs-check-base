<?php

namespace Tests\Unit;

use App\Livewire\Admin\Tools\Tests\StreamChatTest;
use App\Livewire\Articles\Blog\BlogList;
use App\Livewire\Articles\Blog\BlogShow;
use App\Livewire\Tools\Chatbot;
use App\Models\Post;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class BlogVisibilityTest extends TestCase
{
    private const CONNECTION = 'blog_visibility_testing';

    protected function setUp(): void
    {
        parent::setUp();

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
        Queue::fake();

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
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        DB::purge(self::CONNECTION);

        parent::tearDown();
    }

    public function test_migration_disables_the_blog_by_default(): void
    {
        $migration = require database_path('migrations/2026_07_14_000000_add_blog_visibility_setting.php');

        $migration->up();

        $this->assertSame('0', (string) DB::table('settings')
            ->where('type', 'webcontent')
            ->where('key', 'blog_enabled')
            ->value('value'));
    }

    public function test_disabled_blog_routes_return_not_found(): void
    {
        $this->setBlogEnabled(false);
        $post = $this->createPost('blog', true, now()->subMinute());

        $this->getJson(route('blog.index'))->assertNotFound();
        $this->getJson(route('post.show', $post))->assertNotFound();
    }

    public function test_blog_detail_accepts_only_currently_published_blog_posts(): void
    {
        Carbon::setTestNow('2026-07-14 12:00:00');
        $this->setBlogEnabled(true);

        $publishedPost = new Post([
            'type' => 'blog',
            'title' => 'Public blog post',
            'published' => true,
            'published_at' => now()->subMinute(),
        ]);

        $component = new BlogShow;
        $component->mount($publishedPost);

        $this->assertSame($publishedPost, $component->post);

        foreach ([
            new Post(['type' => 'news', 'title' => 'Wrong type', 'published' => true, 'published_at' => now()->subMinute()]),
            new Post(['type' => 'blog', 'title' => 'Draft', 'published' => false, 'published_at' => now()->subMinute()]),
            new Post(['type' => 'blog', 'title' => 'Scheduled', 'published' => true, 'published_at' => now()->addMinute()]),
            new Post(['type' => 'blog', 'title' => 'Missing date', 'published' => true, 'published_at' => null]),
        ] as $hiddenPost) {
            try {
                (new BlogShow)->mount($hiddenPost);
                $this->fail('An unavailable post did not return 404.');
            } catch (NotFoundHttpException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_blog_list_only_contains_currently_published_blog_posts(): void
    {
        Carbon::setTestNow('2026-07-14 12:00:00');
        $this->setBlogEnabled(true);

        $visible = $this->createPost('blog', true, now()->subMinute(), 'visible-blog-post');
        $this->createPost('blog', true, now()->addMinute(), 'scheduled-blog-post');
        $this->createPost('blog', false, now()->subMinute(), 'draft-blog-post');
        $this->createPost('news', true, now()->subMinute(), 'published-news-post');

        $posts = (new BlogList)->render()->getData()['posts'];

        $this->assertSame([$visible->id], $posts->getCollection()->pluck('id')->all());
    }

    public function test_chatbot_only_exposes_blog_navigation_while_enabled(): void
    {
        $chatbot = new class extends Chatbot
        {
            public function exposedNavigationTargets(): array
            {
                return $this->navigationTargets();
            }
        };
        $streamChatTest = new class extends StreamChatTest
        {
            public function exposedNavigationTargets(): array
            {
                return $this->navigationTargets();
            }
        };

        $this->setBlogEnabled(false);
        $this->assertNotContains('blog', $chatbot->exposedNavigationTargets());
        $this->assertNotContains('blog', $streamChatTest->exposedNavigationTargets());

        $this->setBlogEnabled(true);
        $this->assertContains('blog', $chatbot->exposedNavigationTargets());
        $this->assertContains('blog', $streamChatTest->exposedNavigationTargets());
    }

    public function test_livewire_render_is_blocked_after_the_blog_is_switched_off(): void
    {
        $this->setBlogEnabled(true);

        $list = new BlogList;
        $show = new BlogShow;

        $this->setBlogEnabled(false);

        foreach ([$list, $show] as $component) {
            try {
                $component->render();
                $this->fail('A mounted blog component continued rendering after the blog was disabled.');
            } catch (NotFoundHttpException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    private function setBlogEnabled(bool $enabled): void
    {
        DB::table('settings')->updateOrInsert(
            ['type' => 'webcontent', 'key' => 'blog_enabled'],
            [
                'value' => $enabled ? '1' : '0',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function createPost(
        string $type,
        bool $published,
        ?Carbon $publishedAt,
        string $slug = 'blog-route-test'
    ): Post {
        return Post::create([
            'type' => $type,
            'title' => 'Blog route test',
            'slug' => $slug,
            'published' => $published,
            'published_at' => $publishedAt,
        ]);
    }
}
