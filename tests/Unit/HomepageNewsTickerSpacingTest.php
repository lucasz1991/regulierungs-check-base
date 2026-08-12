<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class HomepageNewsTickerSpacingTest extends TestCase
{
    public function test_homepage_news_ticker_uses_its_spacing_below_instead_of_above(): void
    {
        $css = $this->tickerCss();

        $this->assertMatchesRegularExpression(
            '/margin:\s*0\s+0\s+\.75rem\s*;/',
            $css,
            'Der Abstand gehoert unter den Ticker, nicht darueber.'
        );
        $this->assertStringNotContainsString('margin: .5rem calc(50% - 50vw) 0;', $css);
    }

    /**
     * Der Ticker steht als Geschwister der Container-Spalte und braucht den
     * frueheren 100vw-Breakout nicht mehr - der wurde vom overflow-hidden des
     * Containers ohnehin abgeschnitten.
     */
    public function test_ticker_spans_the_full_width_without_a_breakout_hack(): void
    {
        $css = $this->tickerCss();

        $this->assertMatchesRegularExpression('/width:\s*100%\s*;/', $css);
        $this->assertStringNotContainsString('calc(50% - 50vw)', $css);

        $welcome = file_get_contents(resource_path('views/livewire/welcome.blade.php'));
        $banner = 'livewire:banner.homepage-news-teaser-banner';

        $this->assertStringContainsString($banner, $welcome);

        /*
         * Der Ticker muss auf derselben Ebene stehen wie die Container-Spalte.
         * Tags zu zaehlen taugt dafuer nicht, weil Blade-Bedingungen die
         * Bilanz verschieben - die Einrueckung bildet die Schachtelung in
         * dieser Datei dagegen zuverlaessig ab.
         */
        $indentOf = function (string $needle) use ($welcome): int {
            foreach (explode("\n", $welcome) as $line) {
                if (str_contains($line, $needle)) {
                    return strlen($line) - strlen(ltrim($line, ' '));
                }
            }

            return -1;
        };

        $containerIndent = $indentOf('container mx-auto px-4 pt-6 overflow-hidden');
        $bannerIndent = $indentOf($banner);

        $this->assertGreaterThan(-1, $containerIndent);
        $this->assertSame(
            $containerIndent,
            $bannerIndent,
            'Der Ticker gehoert neben die Container-Spalte, nicht hinein - sonst schneidet deren overflow-hidden ihn ab.'
        );
    }

    /**
     * Nahtloser Umlauf: die Spur enthaelt die Sequenz genau zweimal und wandert
     * um exakt -50 %. Der Abstand haengt deshalb an der Karte und nicht als gap
     * an der Spur - sonst saesse der Ruecksprung um einen Abstand daneben.
     */
    public function test_marquee_loops_seamlessly_and_runs_linearly(): void
    {
        $css = $this->tickerCss();

        $this->assertMatchesRegularExpression('/translate3d\(-50%,\s*0,\s*0\)/', $css);
        $this->assertStringContainsString('linear infinite', $css);
        $this->assertMatchesRegularExpression(
            '/\.homepage-news-ticker__card\s*\{[^}]*margin-right:\s*\.75rem\s*;/s',
            $css
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.homepage-news-ticker__track\s*\{[^}]*\bgap:/s',
            $css,
            'Ein gap an der Spur wuerde die Naht um einen Abstand verschieben.'
        );

        $view = file_get_contents(
            resource_path('views/livewire/banner/homepage-news-teaser-banner.blade.php')
        );

        $this->assertStringContainsString('$copy < 2', $view, 'Die Sequenz muss genau doppelt vorliegen.');
        $this->assertStringContainsString(
            '$minimumSequenceCards = 16',
            $view,
            'Auch sehr breite Viewports muessen ohne sichtbare Luecke gefuellt bleiben.'
        );
        $this->assertStringNotContainsString('new Swiper', $view, 'Die Laufschrift kommt ohne Swiper aus.');

        $component = file_get_contents(app_path('Livewire/Banner/HomepageNewsTeaserBanner.php'));

        $this->assertStringNotContainsString('$tickerItems', $component);
        $this->assertStringNotContainsString('$tickerShouldAnimate', $component);
    }

    public function test_ticker_is_another_ten_percent_faster_and_can_be_dragged_with_touch(): void
    {
        $view = file_get_contents(
            resource_path('views/livewire/banner/homepage-news-teaser-banner.blade.php')
        );
        $script = file_get_contents(resource_path('js/homepage-news-ticker.js'));
        $app = file_get_contents(resource_path('js/app.js'));
        $css = $this->tickerCss();

        $this->assertStringContainsString('$tickerPixelsPerSecond = 45 * 1.15 * 1.10;', $view);
        $this->assertStringContainsString('$sequenceWidth / $tickerPixelsPerSecond', $view);
        $this->assertStringContainsString('data-homepage-news-ticker', $view);
        $this->assertStringContainsString("import './homepage-news-ticker'", $app);
        $this->assertStringContainsString("ticker.addEventListener('pointerdown', startDrag)", $script);
        $this->assertStringContainsString("window.addEventListener('pointermove', moveDrag", $script);
        $this->assertStringContainsString("window.addEventListener('pointerup', finishDrag)", $script);
        $this->assertStringContainsString("window.addEventListener('pointercancel', finishDrag)", $script);
        $this->assertStringContainsString("window.addEventListener('click', suppressDraggedClick, true)", $script);
        $this->assertStringContainsString('const tickerStates = new WeakMap()', $script);
        $this->assertStringContainsString('event.target?.closest?.(TICKER_SELECTOR)', $script);
        $this->assertStringContainsString('state.suppressNextClick = true', $script);
        $this->assertStringContainsString('state.suppressNextClick = false', $script);
        $this->assertStringContainsString("ticker.classList.add('is-drag-suppressing')", $script);
        $this->assertStringContainsString("ticker.classList.remove('is-drag-suppressing')", $script);
        $this->assertStringContainsString('track.style.animationPlayState = \'paused\'', $script);
        $this->assertStringContainsString('track.style.animationDelay = `${-(duration * progress)}s`', $script);
        $this->assertStringContainsString('setTrackPosition(state.originX + deltaX)', $script);
        $this->assertStringContainsString("document.addEventListener('livewire:navigated'", $script);
        $this->assertStringContainsString("window.matchMedia('(prefers-reduced-motion: reduce)')", $script);
        $this->assertStringContainsString('draggable="false"', $view);
        $this->assertMatchesRegularExpression('/touch-action:\s*pan-y\s+pinch-zoom\s*;/', $css);
        $this->assertStringContainsString('cursor: grabbing', $css);
        $this->assertStringContainsString('.homepage-news-ticker.is-drag-suppressing a', $css);
        $this->assertStringContainsString('pointer-events: none', $css);

        $post = new Post([
            'title' => 'Touch-Drag Test',
            'slug' => 'touch-drag-test',
            'type' => 'news',
            'excerpt' => 'Der Ticker bleibt anklickbar und kann gezogen werden.',
        ]);
        $post->setRelation('newsCategory', null);

        $html = view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => true,
            'posts' => collect([$post]),
        ])->render();

        $this->assertStringContainsString('--homepage-news-ticker-duration: 93.316s', $html);
        $this->assertStringContainsString('data-homepage-news-ticker', $html);
        $this->assertSame(32, substr_count($html, 'homepage-news-ticker__card'));
    }

    public function test_horizontal_drag_coasts_locks_vertical_scroll_and_restarts_after_two_seconds(): void
    {
        $script = file_get_contents(resource_path('js/homepage-news-ticker.js'));

        $this->assertStringContainsString('const AUTOPLAY_RESTART_DELAY_MS = 2000', $script);
        $this->assertStringContainsString('const MOMENTUM_FRICTION_PER_FRAME = 0.94', $script);
        $this->assertStringContainsString('const MOMENTUM_STOP_VELOCITY = 0.02', $script);
        $this->assertStringContainsString('const MAX_MOMENTUM_DURATION_MS = 1800', $script);
        $this->assertStringContainsString('window.requestAnimationFrame(step)', $script);
        $this->assertStringContainsString('window.cancelAnimationFrame(state.momentumFrame)', $script);
        $this->assertStringContainsString('startMomentum()', $script);
        $this->assertStringContainsString("setMotionState('coasting')", $script);
        $this->assertStringContainsString("setMotionState('waiting')", $script);
        $this->assertStringContainsString('}, AUTOPLAY_RESTART_DELAY_MS)', $script);

        $this->assertStringContainsString("state.axis = 'x'", $script);
        $this->assertStringContainsString("state.axis = 'y'", $script);
        $this->assertStringContainsString('Math.abs(deltaY) >= Math.abs(deltaX)', $script);
        $this->assertStringContainsString(
            "window.addEventListener('touchmove', blockLockedTouchScroll, { passive: false })",
            $script
        );
        $this->assertStringContainsString("window.removeEventListener('touchmove', blockLockedTouchScroll)", $script);
        $this->assertStringContainsString("state.axis !== 'x'", $script);
        $this->assertStringContainsString('|| !state.dragging', $script);
        $this->assertStringContainsString('event.touches?.length ?? 0', $script);
        $this->assertStringContainsString('event.preventDefault()', $script);
        $this->assertStringContainsString("event?.type === 'pointerup'", $script);
        $this->assertStringContainsString('elapsed >= RELEASE_VELOCITY_IDLE_MS', $script);
        $this->assertStringContainsString('activeElement.blur()', $script);
        $this->assertStringContainsString('if (!state.dragging)', $script);
    }

    public function test_hover_pauses_the_marquee(): void
    {
        $css = $this->tickerCss();

        $this->assertMatchesRegularExpression(
            '/@media\s*\(hover:\s*hover\)\s*and\s*\(pointer:\s*fine\)/',
            $css
        );
        $this->assertMatchesRegularExpression(
            '/\.homepage-news-ticker:hover[^{]*\{[^}]*animation-play-state:\s*paused/s',
            $css
        );
    }

    public function test_ticker_never_shows_a_horizontal_scrollbar(): void
    {
        $css = $this->tickerCss();

        $this->assertMatchesRegularExpression('/scrollbar-width:\s*none\s*;/', $css);
        $this->assertMatchesRegularExpression('/-ms-overflow-style:\s*none\s*;/', $css);
        $this->assertMatchesRegularExpression(
            '/\.homepage-news-ticker::\-webkit-scrollbar\s*\{[^}]*display:\s*none\s*;[^}]*height:\s*0\s*;/s',
            $css
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)[^{]*\{.*?\.homepage-news-ticker\s*\{[^}]*overflow-x:\s*auto\s*;/s',
            $css
        );
    }

    private function tickerCss(): string
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);

        $start = strpos($css, '.homepage-news-ticker {');
        $this->assertNotFalse($start, 'Der Ticker-Block fehlt im Stylesheet.');

        return substr($css, $start);
    }
}
