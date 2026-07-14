<?php

namespace Tests\Unit;

use Tests\TestCase;

class HomepageNewsTickerSpacingTest extends TestCase
{
    public function test_homepage_news_ticker_uses_its_spacing_below_instead_of_above(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertMatchesRegularExpression(
            '/\.homepage-news-ticker\s*\{[^}]*margin:\s*0\s+calc\(50%\s*-\s*50vw\)\s+\.75rem\s*;/s',
            $css
        );
        $this->assertStringNotContainsString(
            'margin: .5rem calc(50% - 50vw) 0;',
            $css
        );
    }
}
