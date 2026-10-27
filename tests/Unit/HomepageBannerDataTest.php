<?php

namespace Tests\Unit;

use App\Livewire\Banner\HomepageClaimratingsRandomBanner;
use App\Livewire\Banner\TopInsurancesByTypeBanner;
use App\Models\ClaimRating;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageBannerDataTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        Schema::create('insurances', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('initials')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
        });
        Schema::create('insurance_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->integer('order_id')->nullable();
            $table->string('icon_type')->nullable();
            $table->text('icon_svg')->nullable();
        });
        Schema::create('insurance_subtypes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug');
        });
        Schema::create('insurance_type_insurance_subtype', function (Blueprint $table): void {
            $table->unsignedInteger('insurance_type_id');
            $table->unsignedInteger('insurance_subtype_id');
            $table->integer('order_id')->nullable();
        });
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('role')->default('user');
            $table->json('privacy_settings')->nullable();
        });
        Schema::create('claim_ratings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('insurance_id');
            $table->unsignedInteger('insurance_type_id')->nullable();
            $table->unsignedInteger('insurance_subtype_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('status');
            $table->boolean('is_public');
            $table->decimal('rating_score')->nullable();
            $table->json('answers')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('insurances')->insert([
            ['id' => 1, 'name' => 'Visible insurer', 'slug' => 'visible', 'is_active' => true],
            ['id' => 2, 'name' => 'Private insurer', 'slug' => 'private', 'is_active' => true],
            ['id' => 3, 'name' => 'Inactive insurer', 'slug' => 'inactive', 'is_active' => false],
        ]);
        DB::table('insurance_types')->insert([
            ['id' => 1, 'name' => 'Fahrzeug', 'is_active' => true],
            ['id' => 2, 'name' => 'Wohnen', 'is_active' => true],
            ['id' => 3, 'name' => 'Privat', 'is_active' => true],
        ]);
        DB::table('insurance_subtypes')->insert([
            ['id' => 1, 'name' => 'Kfz', 'slug' => 'kfz'],
            ['id' => 2, 'name' => 'Hausrat', 'slug' => 'hausrat'],
            ['id' => 3, 'name' => 'Privat', 'slug' => 'privat'],
        ]);
        foreach ([1, 2, 3] as $id) {
            DB::table('insurance_type_insurance_subtype')->insert(['insurance_type_id' => $id, 'insurance_subtype_id' => $id]);
        }
    }

    public function test_provider_aggregates_and_type_filters_use_only_visible_ratings(): void
    {
        $this->rating(1, ['rating_score' => 0.4]);
        $this->rating(2, ['status' => 'published', 'rating_score' => 0.8, 'insurance_type_id' => 2, 'insurance_subtype_id' => 2]);
        $this->rating(3, ['is_public' => false, 'rating_score' => 1]);
        $this->rating(4, ['status' => 'pending', 'rating_score' => 1]);
        $this->rating(5, ['deleted_at' => now(), 'rating_score' => 1]);
        $this->rating(6, ['insurance_id' => 2, 'is_public' => false, 'insurance_type_id' => 3, 'insurance_subtype_id' => 3]);
        $this->rating(7, ['insurance_id' => 3, 'insurance_type_id' => 3, 'insurance_subtype_id' => 3]);

        $component = new TopInsurancesByTypeBanner;
        $component->mount();
        $this->assertSame([1], $component->insurances->modelKeys());
        $this->assertSame([1, 2], $component->types->pluck('id')->sort()->values()->all());
        $this->assertSame(2, (int) $component->insurances->first()->published_count);
        $this->assertEqualsWithDelta(0.6, (float) $component->insurances->first()->published_avg, 0.0001);
        $this->assertSame([1, 2], $component->insurances->first()->claimRatings->modelKeys());

        $component->selectInsuranceType(1);
        $this->assertSame([1], $component->selectedSubtypeIds);
        $this->assertSame(1, (int) $component->insurances->first()->published_count);
        $this->assertSame([1], $component->insurances->first()->claimRatings->modelKeys());
        $html = $component->render()->with([
            'types' => $component->types,
            'insurances' => $component->insurances,
            'selectedInsuranceTypeId' => 1,
            'showAllInsuranceTypes' => false,
        ])->render();
        $this->assertStringContainsString('types=1', $html);
        $this->assertStringContainsString('aria-label="2,0 von 5 Sternen"', $html);
        $this->assertStringContainsString('--rating-fill: 40%', $html);

        $component->minPublishedCount = 2;
        $component->selectInsuranceType(1);
        $this->assertCount(0, $component->insurances);
        $component->clearInsuranceTypeFilter();
        $this->assertCount(1, $component->insurances);
    }

    public function test_direct_type_and_related_subtype_keep_the_existing_or_filter(): void
    {
        $this->rating(1, ['insurance_type_id' => 1, 'insurance_subtype_id' => 2]);
        $this->rating(2, ['insurance_type_id' => 2, 'insurance_subtype_id' => 1]);
        $this->rating(3, ['insurance_type_id' => 2, 'insurance_subtype_id' => 2]);
        $component = new TopInsurancesByTypeBanner;
        $component->mount();
        $component->selectInsuranceType(1);
        $this->assertSame(2, (int) $component->insurances->first()->published_count);
        $this->assertSame([1, 2], $component->insurances->first()->claimRatings->modelKeys());
    }

    public function test_livewire_refresh_restores_aggregates_and_constrained_relations(): void
    {
        $this->rating(1, ['rating_score' => 0.8]);
        $this->rating(2, ['is_public' => false, 'rating_score' => 0.2]);
        $component = Livewire::test(TopInsurancesByTypeBanner::class)
            ->assertSeeHtml('aria-label="4,0 von 5 Sternen"');

        $component->set('showAllInsuranceTypes', true)
            ->assertSeeHtml('aria-label="4,0 von 5 Sternen"')
            ->assertSee('1 Bewertung');

        DB::table('claim_ratings')->where('id', 1)->update(['is_public' => false]);
        $component->call('$refresh')->assertDontSee('Visible insurer');
    }

    public function test_review_selection_and_rendering_preserve_visibility_privacy_and_escaping(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Hidden name', 'role' => 'user', 'privacy_settings' => json_encode(['ratings' => ['name_visibility' => 'none']])],
            ['id' => 2, 'name' => 'Public name', 'role' => 'user', 'privacy_settings' => json_encode(['ratings' => ['name_visibility' => 'all']])],
            ['id' => 3, 'name' => 'Admin name', 'role' => 'admin', 'privacy_settings' => json_encode(['ratings' => ['name_visibility' => 'all']])],
        ]);
        $this->rating(1, ['user_id' => 1, 'attachments' => json_encode(['scorings' => ['ai_overall_comment' => '<img src=x onerror=alert(1)>']])]);
        $this->rating(2, ['user_id' => 2, 'status' => 'published', 'rating_score' => 0.8]);
        $this->rating(3, ['user_id' => 3]);
        $this->rating(4, ['is_public' => false]);
        $this->rating(5, ['status' => 'rejected']);
        $this->rating(6, ['deleted_at' => now()]);

        $component = new HomepageClaimratingsRandomBanner;
        $component->mount();
        $this->assertSame([1, 2, 3], $component->claimRatings->pluck('id')->sort()->values()->all());
        $html = $component->render()->with('claimRatings', $component->claimRatings)->render();
        $this->assertStringNotContainsString('Hidden name', $html);
        $this->assertStringNotContainsString('Admin name', $html);
        $this->assertStringContainsString('Public name', $html);
        $this->assertStringContainsString('Anonym', $html);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $html);
        $this->assertStringNotContainsString('<img src=x', $html);
        $this->assertStringContainsString('aria-label="4,0 von 5 Sternen"', $html);
        $this->assertStringContainsString('--rating-fill: 80%', $html);
    }

    public function test_unknown_duration_is_unavailable_and_real_zero_days_is_not_dropped(): void
    {
        $this->rating(1, ['answers' => null]);
        $component = new TopInsurancesByTypeBanner;
        $component->mount();
        $data = ['types' => $component->types, 'insurances' => $component->insurances, 'selectedInsuranceTypeId' => null, 'showAllInsuranceTypes' => false];
        $html = view('livewire.banner.top-insurances-by-type-banner', $data)->render();
        $this->assertStringContainsString('<strong>– ', $html);

        DB::table('claim_ratings')->where('id', 1)->update(['answers' => json_encode(['selectedDates' => ['started_at' => '2026-10-01', 'ended_at' => '2026-10-01'], 'is_closed' => true])]);
        $component->clearInsuranceTypeFilter();
        $data['insurances'] = $component->insurances;
        $html = view('livewire.banner.top-insurances-by-type-banner', $data)->render();
        $this->assertStringContainsString('<strong>0 ', $html);
        $this->assertStringContainsString('<span>Tage</span>', $html);
    }

    public function test_generic_middle_content_is_not_duplicated_by_fallback_and_real_footer_form_is_preserved(): void
    {
        $this->createPagebuilderSchema();
        $newsletterHtml = '<section><h1 id="cms-newsletter-title">Regulierungs-CHECK'."\u{00A0}".'Newsletter</h1><form action="/form-submit" method="POST"><input id="csrf-token-input" name="_token"><input name="email" type="email"><button type="submit">anmelden</button></form></section>';
        DB::table('pagebuilder_projects')->insert([
            [
                'id' => 25,
                'page' => json_encode(['start']),
                'position' => json_encode(['content_between_2']),
                'status' => 1,
                'cleaned_html' => '<section><h1 id="cms-about-title">Was ist Regulierungs-CHECK?</h1><p>CMS-Inhalt</p></section>',
                'css' => '',
                'js' => '',
            ],
            [
                'id' => 30,
                'page' => json_encode(['start', 'aboutus']),
                'position' => json_encode(['footer']),
                'status' => 1,
                'cleaned_html' => $newsletterHtml,
                'css' => '#cms-newsletter-title{color:inherit}',
                'js' => 'document.getElementById("csrf-token-input").value = "test-token";',
            ],
        ]);

        $html = $this->renderHomepageMiddleContent();
        $this->assertSame(1, substr_count($html, 'Was ist Regulierungs-CHECK?'));
        $this->assertStringContainsString('homepage-cms homepage-cms-content', $html);
        $this->assertStringContainsString('<h2 id="cms-about-title">', $html);
        $this->assertStringNotContainsString('about-illustration', $html);
        $this->assertStringNotContainsString('newsletter-section', $html);

        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $this->assertSame(1, preg_match('/@php\(\$homepageFooterModule\b[\s\S]*?@endif/', $layout, $match));
        $footer = Blade::render($match[0]);
        $this->assertStringContainsString('homepage-cms homepage-cms-footer', $footer);
        $this->assertStringContainsString('<h2 id="cms-newsletter-title">', $footer);
        $this->assertStringContainsString('<span class="newsletter-brand">Regulierungs-CHECK</span> Newsletter', $footer);
        $this->assertSame(1, substr_count($footer, '<form '));
        $this->assertStringContainsString('action="/form-submit" method="POST"', $footer);
        $this->assertStringContainsString('id="csrf-token-input" name="_token"', $footer);
        $this->assertStringContainsString('name="email" type="email"', $footer);
        $this->assertStringContainsString('document.getElementById("csrf-token-input").value = "test-token";', $footer);
        $this->assertStringContainsString('#cms-newsletter-title{color:inherit}', $footer);
        $this->assertStringContainsString('<h1 id="cms-about-title">', DB::table('pagebuilder_projects')->where('id', 25)->value('cleaned_html'));
        $this->assertSame($newsletterHtml, DB::table('pagebuilder_projects')->where('id', 30)->value('cleaned_html'));
    }

    public function test_empty_homepage_cms_keeps_the_about_and_newsletter_fallbacks(): void
    {
        $this->createPagebuilderSchema();

        $html = $this->renderHomepageMiddleContent();

        $this->assertSame(1, substr_count($html, 'id="about-title"'));
        $this->assertSame(1, substr_count($html, 'id="newsletter-title"'));
        $this->assertStringNotContainsString('homepage-cms-content', $html);
    }

    public function test_homepage_heading_normalization_preserves_raw_text_attributes_and_other_pages(): void
    {
        $moduleHtml = '<section data-example="<h1>literal</h1>"><!-- <h1>comment</h1> --><script>const example = "<h1>script</h1>";</script><h1 id="actual-title" class="native">Titel</h1><textarea><h1>textarea</h1></textarea></section>';
        $data = ['position' => 'content_between_2', 'modules' => collect([(object) ['id' => 7, 'cleaned_html' => $moduleHtml, 'css' => '', 'js' => '']])];

        $homepage = view('components.pagebuilder-module', $data + ['homepageModuleContext' => 'content'])->render();
        $this->assertStringContainsString('<h2 id="actual-title" class="native">Titel</h2>', $homepage);
        $this->assertStringContainsString('data-example="<h1>literal</h1>"', $homepage);
        $this->assertStringContainsString('<!-- <h1>comment</h1> -->', $homepage);
        $this->assertStringContainsString('<script>const example = "<h1>script</h1>";</script>', $homepage);
        $this->assertStringContainsString('<textarea><h1>textarea</h1></textarea>', $homepage);

        $otherPage = view('components.pagebuilder-module', $data)->render();
        $this->assertStringContainsString($moduleHtml, $otherPage);
        $this->assertStringNotContainsString('homepage-cms', $otherPage);
    }

    public function test_footer_brand_wrap_does_not_change_attributes_or_raw_text_content(): void
    {
        $brandWithNbsp = 'Regulierungs-CHECK'."\u{00A0}".'Newsletter';
        $moduleHtml = '<section data-brand="'.$brandWithNbsp.'"><!-- Regulierungs-CHECK&nbsp;Newsletter --><script>const title = "Regulierungs-CHECK&nbsp;Newsletter";</script><style>/* Regulierungs-CHECK&nbsp;Newsletter */</style><textarea>Regulierungs-CHECK&nbsp;Newsletter</textarea><h1 id="brand-title">'.$brandWithNbsp.'</h1><p>Regulierungs-CHECK&nbsp;Newsletter</p></section>';
        $module = (object) ['id' => 30, 'cleaned_html' => $moduleHtml, 'css' => '', 'js' => ''];
        $data = ['position' => 'footer', 'modules' => collect([$module])];

        $footer = view('components.pagebuilder-module', $data + ['homepageModuleContext' => 'footer'])->render();

        $this->assertSame(2, substr_count($footer, 'class="newsletter-brand"'));
        $this->assertStringContainsString('<h2 id="brand-title"><span class="newsletter-brand">Regulierungs-CHECK</span> Newsletter</h2>', $footer);
        $this->assertStringContainsString('<p><span class="newsletter-brand">Regulierungs-CHECK</span> Newsletter</p>', $footer);
        $this->assertStringContainsString('data-brand="'.$brandWithNbsp.'"', $footer);
        $this->assertStringContainsString('<!-- Regulierungs-CHECK&nbsp;Newsletter -->', $footer);
        $this->assertStringContainsString('<script>const title = "Regulierungs-CHECK&nbsp;Newsletter";</script>', $footer);
        $this->assertStringContainsString('<style>/* Regulierungs-CHECK&nbsp;Newsletter */</style>', $footer);
        $this->assertStringContainsString('<textarea>Regulierungs-CHECK&nbsp;Newsletter</textarea>', $footer);
        $this->assertSame($moduleHtml, $module->cleaned_html);

        $content = view('components.pagebuilder-module', $data + ['homepageModuleContext' => 'content'])->render();
        $this->assertStringNotContainsString('class="newsletter-brand"', $content);
        $this->assertStringContainsString($brandWithNbsp, $content);
    }

    private function createPagebuilderSchema(): void
    {
        Schema::create('pagebuilder_projects', function (Blueprint $table): void {
            $table->id();
            $table->json('page');
            $table->json('position');
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('lang')->nullable();
            $table->timestamp('published_from')->nullable();
            $table->timestamp('published_until')->nullable();
            $table->integer('order_id')->default(0);
            $table->text('cleaned_html');
            $table->text('css')->nullable();
            $table->text('js')->nullable();
            $table->softDeletes();
        });
    }

    private function renderHomepageMiddleContent(): string
    {
        $source = file_get_contents(resource_path('views/livewire/welcome.blade.php'));
        $start = strpos($source, '    @php');
        $end = strpos($source, '    <livewire:customer.rating.rating-form');
        $this->assertNotFalse($start);
        $this->assertNotFalse($end);

        return Blade::render(substr($source, $start, $end - $start));
    }

    private function rating(int $id, array $overrides = []): void
    {
        DB::table('claim_ratings')->insert(array_merge([
            'id' => $id,
            'insurance_id' => 1,
            'insurance_type_id' => 1,
            'insurance_subtype_id' => 1,
            'status' => ClaimRating::STATUS_RATED,
            'is_public' => true,
            'rating_score' => 0.6,
            'answers' => json_encode(['selectedDates' => ['started_at' => '2026-10-01', 'ended_at' => '2026-10-11'], 'is_closed' => true]),
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }
}
