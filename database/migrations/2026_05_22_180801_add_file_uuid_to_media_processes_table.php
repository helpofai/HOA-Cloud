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
        Schema::table('media_processes', function (Blueprint $table) {
            $table->uuid('file_uuid')->nullable()->after('file_id');
            $table->index('file_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_processes', function (Blueprint $table) {
            $table->dropColumn('file_uuid');
        });
    }
};
