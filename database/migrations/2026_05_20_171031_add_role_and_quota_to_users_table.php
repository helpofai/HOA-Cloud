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
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->string('role')->default('user')->after('email'); // super-admin, admin, editor, pro, user
            $blueprint->bigInteger('quota_limit')->default(10737418240)->after('role'); // Default 10GB in bytes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['role', 'quota_limit']);
        });
    }
};
