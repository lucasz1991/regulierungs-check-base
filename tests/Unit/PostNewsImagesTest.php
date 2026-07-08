<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class PostNewsImagesTest extends TestCase
{
    public function test_news_images_are_sorted_and_normalized(): void
    {
        $post = new Post([
            'title' => 'Test News',
            'images' => [
                ['path' => 'uploads/news/b.jpg', 'alt' => 'B', 'caption' => 'Second', 'sort' => 2],
                ['path' => 'uploads/news/a.jpg', 'alt' => 'A', 'caption' => 'First', 'sort' => 1],
            ],
        ]);

        $images = $post->newsImages();

        $this->assertSame('uploads/news/a.jpg', $images[0]['path']);
        $this->assertSame('A', $images[0]['alt']);
        $this->assertStringContainsString('/storage/uploads/news/a.jpg', $images[0]['url']);
        $this->assertSame('uploads/news/b.jpg', $images[1]['path']);
    }

    public function test_first_news_image_falls_back_to_cover_image(): void
    {
        $post = new Post([
            'title' => 'Cover News',
            'cover_image' => 'uploads/news/cover.jpg',
        ]);

        $image = $post->firstNewsImage();

        $this->assertSame('uploads/news/cover.jpg', $image['path']);
        $this->assertSame('Cover News', $image['alt']);
    }
}
