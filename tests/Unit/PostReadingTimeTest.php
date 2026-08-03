<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class PostReadingTimeTest extends TestCase
{
    public function test_manually_set_reading_time_wins_over_the_estimate(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            'reading_time_minutes' => 7,
            // Vieler Text, der eine deutlich andere Schaetzung ergaebe.
            'body' => '<p>'.str_repeat('Wort ', 2000).'</p>',
        ]);

        $this->assertSame(7, $post->reading_time_minutes);
        $this->assertTrue($post->hasManualReadingTime());
        $this->assertNotSame(7, $post->estimatedReadingTimeMinutes());
    }

    public function test_empty_reading_time_falls_back_to_the_estimate(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            'body' => '<p>'.str_repeat('Wort ', 400).'</p>',
        ]);

        $this->assertFalse($post->hasManualReadingTime());
        $this->assertSame(2, $post->reading_time_minutes);
    }

    public function test_zero_is_treated_as_not_set(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            'reading_time_minutes' => 0,
            'body' => '<p>'.str_repeat('Wort ', 400).'</p>',
        ]);

        $this->assertFalse($post->hasManualReadingTime());
        $this->assertSame(2, $post->reading_time_minutes);
    }

    public function test_estimate_never_drops_below_one_minute(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            'body' => '<p>Kurz.</p>',
        ]);

        $this->assertSame(1, $post->reading_time_minutes);
    }

    /**
     * str_word_count() ist nicht UTF-8-faehig und zerlegte jedes Wort mit
     * Umlaut in mehrere Treffer, was die Schaetzung nach oben trieb.
     */
    public function test_estimate_counts_german_umlauts_as_single_words(): void
    {
        $satz = 'Das Urteil betrifft Schadensfälle und Versicherungsnehmer über Jahre größer. ';

        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            // 9 Woerter pro Satz, 100 Saetze = 900 Woerter -> 5 Minuten.
            'body' => '<p>'.str_repeat($satz, 100).'</p>',
        ]);

        $this->assertSame(5, $post->estimatedReadingTimeMinutes());
    }

    /**
     * strip_tags() entfernte Tags ersatzlos, wodurch benachbarte Bloecke
     * zu einem Wort verschmolzen und die Schaetzung nach unten zogen.
     */
    public function test_estimate_does_not_glue_adjacent_blocks_together(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            // 400 Bloecke mit je einem Wort. Ohne Trennung waere es ein Wort.
            'body' => str_repeat('<p>Wort</p>', 400),
        ]);

        $this->assertSame(2, $post->estimatedReadingTimeMinutes());
    }

    public function test_html_entities_do_not_inflate_the_word_count(): void
    {
        $post = new Post([
            'type' => 'news',
            'title' => 'Synthetic News',
            'body' => '<p>'.str_repeat('Recht &amp; Urteile ', 200).'</p>',
        ]);

        // 200 x 3 Woerter = 600 -> 3 Minuten. Ohne Entity-Dekodierung waere
        // "&amp;" ein eigenes, laengeres Token geblieben.
        $this->assertSame(3, $post->estimatedReadingTimeMinutes());
    }
}
