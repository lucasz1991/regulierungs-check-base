<?php

namespace Tests\Feature;

use App\Livewire\Tools\SearchModal;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class HomepageNavigationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->text('value')->nullable();
        });
    }

    public function test_guest_header_preserves_native_routes_and_content_flags(): void
    {
        $html = view('components.homepage.header')->render();

        foreach (['home', 'insurances', 'reviews', 'guidance', 'faqs', 'aboutus', 'howto', 'abos', 'contact', 'login', 'register'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $html);
        }
        $this->assertStringNotContainsString('href="'.route('blog.index').'"', $html);
        $this->assertStringNotContainsString('href="'.route('news.index').'"', $html);
        $this->assertStringNotContainsString('action="'.route('logout').'"', $html);
        $this->assertStringContainsString('aria-label="Suche öffnen"', $html);

        DB::table('settings')->insert([
            ['type' => 'webcontent', 'key' => 'blog_enabled', 'value' => 'true'],
            ['type' => 'webcontent', 'key' => 'news_enabled', 'value' => 'true'],
        ]);
        $enabled = view('components.homepage.header')->render();
        $this->assertStringContainsString('href="'.route('blog.index').'"', $enabled);
        $this->assertStringContainsString('href="'.route('news.index').'"', $enabled);
    }

    public function test_authenticated_customer_sees_native_account_messages_and_csrf_logout(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->forceFill(['id' => 123, 'name' => 'Test <Kunde>', 'email' => 'test@example.invalid', 'role' => 'guest']);
        $user->shouldReceive('receivedUnreadMessages')->once()->andReturn(new class {
            public function count(): int { return 2; }
        });
        $this->actingAs($user);
        session()->put('_token', 'navigation-test-token');

        $html = view('components.homepage.header')->render();

        foreach (['dashboard', 'profile.show', 'messages'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $html);
        }
        $this->assertStringContainsString('Test &lt;Kunde&gt;', $html);
        $this->assertStringContainsString('homepage-message-count">2</span>', $html);
        $this->assertStringContainsString('method="POST" action="'.route('logout').'"', $html);
        $this->assertStringContainsString('value="navigation-test-token"', $html);
        $this->assertStringNotContainsString('href="'.route('login').'"', $html);
    }

    public function test_footer_preserves_native_legal_and_contact_routes(): void
    {
        $html = view('components.homepage.footer')->render();

        foreach (['home', 'insurances', 'reviews', 'aboutus', 'howto', 'abos', 'faqs', 'contact', 'terms', 'privacypolicy', 'imprint'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $html);
        }
    }

    public function test_search_trigger_remains_default_and_can_be_omitted_for_homepage(): void
    {
        Livewire::test(SearchModal::class)
            ->assertSet('showTrigger', true)
            ->assertSeeHtml('@click.prevent="openSearchMenu = !openSearchMenu"');

        Livewire::test(SearchModal::class, ['showTrigger' => false])
            ->assertSet('showTrigger', false)
            ->assertDontSeeHtml('@click.prevent="openSearchMenu = !openSearchMenu"')
            ->assertSeeHtml('wire:model.live="query"')
            ->assertSeeHtml('aria-label="Suche schließen"');
    }
}
