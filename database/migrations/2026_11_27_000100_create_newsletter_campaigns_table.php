<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('draft_key')->nullable()->unique();
            $table->unsignedBigInteger('post_id')->nullable()->index();
            $table->string('article_title')->nullable();
            $table->string('name');
            $table->string('subject');
            $table->string('preheader')->nullable();
            $table->text('body');
            $table->text('link')->nullable();
            $table->string('button_label', 80)->nullable();
            $table->string('greeting')->nullable();
            $table->string('audience', 12)->default('all');
            $table->text('image_source')->nullable();
            $table->text('image_url')->nullable();
            $table->text('image_alt')->nullable();
            $table->unsignedInteger('image_width')->nullable();
            $table->unsignedInteger('image_height')->nullable();
            $table->unsignedTinyInteger('focus_x')->default(50);
            $table->unsignedTinyInteger('focus_y')->default(50);
            $table->boolean('include_image')->default(false);
            $table->string('status', 16)->default('draft');
            $table->dateTime('scheduled_at')->nullable();
            $table->unsignedInteger('revision')->default(1);
            $table->timestamps();
            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
    }
};
