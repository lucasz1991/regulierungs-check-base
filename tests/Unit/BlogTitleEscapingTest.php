<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BlogTitleEscapingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('likes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('likeable_type');
            $table->unsignedBigInteger('likeable_id');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('body')->nullable();
            $table->string('commentable_type');
            $table->unsignedBigInteger('commentable_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
    }

    public function test_public_blog_card_escapes_a_persisted_title(): void
    {
        $post = $this->postWithMaliciousTitle();

        $html = view('components.blog.blog-card', ['post' => $post])->render();

        $this->assertStringContainsString(e($post->title), $html);
        $this->assertStringNotContainsString($post->title, $html);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
    }

    public function test_public_blog_detail_escapes_a_persisted_title(): void
    {
        $post = $this->postWithMaliciousTitle();

        $html = view('livewire.articles.blog.blog-show', ['post' => $post])->render();

        $this->assertStringContainsString(e($post->title), $html);
        $this->assertStringNotContainsString($post->title, $html);
        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
    }

    public function test_public_blog_detail_sanitizes_persisted_legacy_html_but_keeps_formatting(): void
    {
        $post = $this->postWithMaliciousTitle();
        $post->title = 'Sicherer Titel';
        $post->body = '<h2>Legitime Überschrift</h2><p onmouseover="alert(1)"><strong>Text</strong>'
            .'<img src=x onerror="alert(2)"><a href="javascript:alert(3)">Link</a></p><script>alert(4)</script>';

        $html = view('livewire.articles.blog.blog-show', ['post' => $post])->render();

        $this->assertStringContainsString('<h2>Legitime Überschrift</h2>', $html);
        $this->assertStringContainsString('<strong>Text</strong>', $html);
        $this->assertStringContainsString('<a>Link</a>', $html);
        $this->assertStringNotContainsString('onmouseover', $html);
        $this->assertStringNotContainsString('onerror', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('<script>alert(4)</script>', $html);
    }

    private function postWithMaliciousTitle(): Post
    {
        $post = new Post;
        $post->forceFill([
            'title' => '"><img src=x onerror=alert(1)>',
            'slug' => 'escaped-blog-title',
            'excerpt' => 'Sichere Vorschau',
            'body' => '<p>Sicherer redaktioneller Inhalt.</p>',
            'cover_image' => null,
            'published_at' => now(),
        ]);
        $post->id = 123;
        $post->exists = true;

        return $post;
    }
}
