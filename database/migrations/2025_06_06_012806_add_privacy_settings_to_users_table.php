<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('privacy_settings')->nullable()->default(json_encode([
                'comments' => [
                    'name_visibility' => 'users',
                    'avatar_visibility' => 'users',
                ],
                'ratings' => [
                    'name_visibility' => 'users',
                    'avatar_visibility' => 'users',
                ],
            ]));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
