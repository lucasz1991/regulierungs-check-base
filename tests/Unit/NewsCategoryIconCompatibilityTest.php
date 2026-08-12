<?php

namespace Tests\Unit;

use Tests\TestCase;

class NewsCategoryIconCompatibilityTest extends TestCase
{
    public function test_public_styles_support_the_saved_font_awesome_six_scale_alias(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));
        $fontAwesome = file_get_contents(public_path('adminresources/fontawesome6/css/all.min.css'));

        $this->assertIsString($css);
        $this->assertIsString($fontAwesome);
        $this->assertStringNotContainsString(
            '.fa-scale-balanced:before',
            $fontAwesome,
            'Die derzeit ausgelieferte Font-Awesome-5-Datei kennt den gespeicherten FA6-Namen nicht.'
        );
        $this->assertStringContainsString(
            '.fa-balance-scale:before{content:"\f24e"}',
            $fontAwesome,
            'Der zugrunde liegende Waagen-Codepoint muss in Font Awesome 5 vorhanden sein.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fa-scale-balanced::before\s*\{\s*content:\s*"\\\\f24e"\s*;\s*\}/',
            $css,
            'Das öffentliche Stylesheet muss den gespeicherten FA6-Namen auf den vorhandenen FA5-Codepoint abbilden.'
        );
    }
}
