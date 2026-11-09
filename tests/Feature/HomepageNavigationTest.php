<?php

namespace Tests\Feature;

use App\Livewire\Pages\Sitemap;
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
        $user->shouldReceive('receivedUnreadMessages')->once()->andReturn(new class
        {
            public function count(): int
            {
                return 2;
            }
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

    public function test_search_explains_the_minimum_query_and_keeps_short_queries_empty(): void
    {
        Livewire::test(SearchModal::class, ['showTrigger' => false])
            ->assertSee('Was möchtest du finden?')
            ->assertSee('Mindestens 2 Zeichen eingeben.')
            ->set('query', 'a')
            ->assertSet('resultsInsurances', [])
            ->assertSet('resultsTypes', [])
            ->assertSet('resultsInfos', [])
            ->assertSee('Mindestens 2 Zeichen eingeben.');
    }

    public function test_search_results_are_keyboard_buttons_and_escape_content(): void
    {
        $this->createSearchFixtures();

        $component = Livewire::test(SearchModal::class, ['showTrigger' => false])
            ->set('query', 'Schaden')
            ->assertSee('Schaden <Partner>')
            ->assertDontSeeHtml('Schaden <Partner>')
            ->assertDontSee('Schaden Inaktiv')
            ->assertDontSee('Schaden Intern')
            ->assertSeeHtml('aria-label="Suchbegriff löschen"');

        $document = new \DOMDocument;
        $document->loadHTML('<?xml encoding="UTF-8">'.$component->html(), LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        $resultActions = [];
        $clearActions = [];

        foreach ($document->getElementsByTagName('button') as $button) {
            $action = $button->getAttribute('wire:click');

            if (preg_match('/^selectResult\(\s*(\d+)\s*,\s*[\'"](insurances|infos)[\'"]\s*\)$/', $action, $match)) {
                $this->assertSame('button', $button->getAttribute('type'));
                $this->assertNotSame('-1', $button->getAttribute('tabindex'));
                $resultActions[] = $match[2].':'.$match[1];
            }

            if ($button->getAttribute('aria-label') === 'Suchbegriff löschen') {
                $this->assertSame('button', $button->getAttribute('type'));
                $clearActions[] = $action;
            }
        }

        sort($resultActions);
        $this->assertSame(['infos:11', 'infos:13', 'insurances:1', 'insurances:3'], $resultActions);
        $this->assertSame(['$set(\'query\', \'\')'], $clearActions);
    }

    public function test_search_category_filters_and_clearing_keep_results_consistent(): void
    {
        $this->createSearchFixtures();

        $component = Livewire::test(SearchModal::class)
            ->set('query', 'Schaden')
            ->assertSet('resultsInsurances', fn ($results) => $results->modelKeys() === [1, 3])
            ->assertSet('resultsInfos', fn ($results) => $results->modelKeys() === [11, 13])
            ->call('selectSearchType', 'insurances')
            ->assertSet('searchType', 'insurances')
            ->assertSet('resultsInsurances', fn ($results) => $results->modelKeys() === [1, 3])
            ->assertSet('resultsInfos', [])
            ->call('selectSearchType', 'infos')
            ->assertSet('searchType', 'infos')
            ->assertSet('resultsInsurances', [])
            ->assertSet('resultsInfos', fn ($results) => $results->modelKeys() === [11, 13])
            ->set('query', '')
            ->assertSet('resultsInsurances', [])
            ->assertSet('resultsTypes', [])
            ->assertSet('resultsInfos', [])
            ->assertSee('Mindestens 2 Zeichen eingeben.');

        $component->set('query', 'keintreffer12345')
            ->assertSet('resultsInsurances', fn ($results) => count($results) === 0)
            ->assertSet('resultsInfos', fn ($results) => count($results) === 0)
            ->assertSee('Keine Ergebnisse');
    }

    public function test_search_result_selection_preserves_insurer_and_information_routes(): void
    {
        $this->createSearchFixtures();

        Livewire::test(SearchModal::class)
            ->call('selectResult', 1, 'insurances')
            ->assertRedirect(route('insurance.show-insurance', ['insurance' => 'schaden-partner']));

        Livewire::test(SearchModal::class)
            ->call('selectResult', 11, 'infos')
            ->assertRedirect(url('/schadenhilfe'));
    }

    public function test_sitemap_renders_existing_public_routes_without_legacy_route_failures(): void
    {
        $component = Livewire::test(Sitemap::class)
            ->assertSee('Leistungs-CHECK')
            ->assertSee('Informationen')
            ->assertSee('Rechtliches');

        foreach (['home', 'insurances', 'reviews', 'ranking', 'guidance', 'faqs', 'howto', 'aboutus', 'abos', 'contact', 'terms', 'privacypolicy', 'imprint'] as $route) {
            $component->assertSeeHtml('href="'.route($route).'"');
        }

        $component->assertDontSeeHtml('href="'.route('news.index').'"')
            ->assertDontSeeHtml('href="'.route('blog.index').'"');
    }

    public function test_sitemap_optional_article_links_follow_current_visibility_settings(): void
    {
        DB::table('settings')->insert([
            ['type' => 'webcontent', 'key' => 'news_enabled', 'value' => 'true'],
            ['type' => 'webcontent', 'key' => 'blog_enabled', 'value' => 'true'],
        ]);

        $component = Livewire::test(Sitemap::class)
            ->assertSeeHtml('href="'.route('news.index').'"')
            ->assertSeeHtml('href="'.route('blog.index').'"');

        DB::table('settings')->whereIn('key', ['news_enabled', 'blog_enabled'])->update(['value' => 'false']);

        $component->call('$refresh')
            ->assertDontSeeHtml('href="'.route('news.index').'"')
            ->assertDontSeeHtml('href="'.route('blog.index').'"')
            ->assertSeeHtml('href="'.route('terms').'"')
            ->assertSeeHtml('href="'.route('privacypolicy').'"')
            ->assertSeeHtml('href="'.route('imprint').'"');
    }

    private function createSearchFixtures(): void
    {
        Schema::create('insurances', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('pagebuilder_projects', function (Blueprint $table): void {
            $table->id();
            $table->text('html');
            $table->text('cleaned_html');
            $table->softDeletes();
        });

        Schema::create('web_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->boolean('is_fixed')->default(true);
            $table->unsignedInteger('pagebuilder_project')->nullable();
        });

        DB::table('insurances')->insert([
            ['id' => 1, 'name' => 'Schaden <Partner>', 'slug' => 'schaden-partner', 'description' => '', 'is_active' => true],
            ['id' => 2, 'name' => 'Schaden Inaktiv', 'slug' => 'schaden-inaktiv', 'description' => '', 'is_active' => false],
            ['id' => 3, 'name' => 'Praxis Direkt', 'slug' => 'praxis-direkt', 'description' => 'Schadenservice', 'is_active' => true],
        ]);

        DB::table('pagebuilder_projects')->insert([
            'id' => 21,
            'html' => '<p>Schadenunterstützung</p>',
            'cleaned_html' => '<p>Schadenunterstützung</p>',
        ]);

        DB::table('web_pages')->insert([
            ['id' => 11, 'title' => 'Schadenhilfe', 'slug' => 'schadenhilfe', 'is_fixed' => true, 'pagebuilder_project' => null],
            ['id' => 12, 'title' => 'Schaden Intern', 'slug' => 'schaden-intern', 'is_fixed' => false, 'pagebuilder_project' => null],
            ['id' => 13, 'title' => 'Kontakt', 'slug' => 'kontakt', 'is_fixed' => true, 'pagebuilder_project' => 21],
        ]);
    }
}
