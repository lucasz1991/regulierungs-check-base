<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['type' => 'webcontent', 'key' => 'blog_enabled'],
            [
                'value' => '0',
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('type', 'webcontent')
            ->where('key', 'blog_enabled')
            ->delete();
    }
};
