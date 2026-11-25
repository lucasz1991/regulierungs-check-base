<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable()->unique();
                foreach (['company', 'phone', 'address', 'city', 'postal_code', 'country', 'category', 'website'] as $field) {
                    $table->string($field)->nullable();
                }
                $table->json('additional_data')->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasColumn('contacts', 'mail_permission')) {
            Schema::table('contacts', fn (Blueprint $table) => $table->string('mail_permission')->default('unknown'));
        }
        if (! Schema::hasColumn('mails', 'dispatch_key')) {
            Schema::table('mails', fn (Blueprint $table) => $table->string('dispatch_key', 80)->nullable()->unique());
        }
    }

    public function down(): void
    {
        Schema::table('mails', function (Blueprint $table) {
            $table->dropUnique(['dispatch_key']);
            $table->dropColumn('dispatch_key');
        });
        // Contacts may predate this migration or contain imported address data.
    }
};
