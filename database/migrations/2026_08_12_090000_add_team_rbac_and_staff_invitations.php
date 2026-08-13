<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('teams', 'rbac_permissions')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->json('rbac_permissions')->nullable()->after('personal_team');
            });
        }

        if (! Schema::hasTable('staff_invitations')) {
            Schema::create('staff_invitations', function (Blueprint $table) {
                $table->id();
                $table->string('email')->index();
                $table->char('token_hash', 64)->unique();
                $table->foreignId('team_id')
                    ->constrained('teams')
                    ->restrictOnDelete();
                $table->foreignId('invited_by')
                    ->constrained('users')
                    ->restrictOnDelete();
                $table->string('role', 32)->default('staff');
                $table->string('position', 100)->nullable();
                $table->dateTime('expires_at');
                $table->dateTime('accepted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_invitations');

        if (Schema::hasColumn('teams', 'rbac_permissions')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropColumn('rbac_permissions');
            });
        }
    }
};
