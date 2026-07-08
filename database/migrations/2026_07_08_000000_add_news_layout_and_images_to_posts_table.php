<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('layout')->default('image_top');
            $table->json('images')->nullable();
            $table->index(['type', 'published', 'published_at'], 'posts_news_listing_index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_news_listing_index');
            $table->dropColumn(['layout', 'images']);
        });
    }
};
