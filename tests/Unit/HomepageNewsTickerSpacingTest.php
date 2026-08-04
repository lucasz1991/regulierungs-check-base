<?php

namespace Tests\Unit;

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
        $this->assertStringNotContainsString('new Swiper', $view, 'Die Laufschrift kommt ohne JavaScript aus.');

        $component = file_get_contents(app_path('Livewire/Banner/HomepageNewsTeaserBanner.php'));

        $this->assertStringNotContainsString('$tickerItems', $component);
        $this->assertStringNotContainsString('$tickerShouldAnimate', $component);
    }

    public function test_hover_pauses_the_marquee(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.homepage-news-ticker:hover[^{]*\{[^}]*animation-play-state:\s*paused/s',
            $this->tickerCss()
        );
    }

    private function tickerCss(): string
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);

        $start = strpos($css, '.homepage-news-ticker {');
        $this->assertNotFalse($start, 'Der Ticker-Block fehlt im Stylesheet.');

        return substr($css, $start, 2500);
    }
}
