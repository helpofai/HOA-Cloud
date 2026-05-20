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
        Schema::table('files', function (Blueprint $table) {
            $table->string('poster_path')->nullable()->after('hash');
            $table->string('backdrop_path')->nullable()->after('poster_path');
            $table->text('overview')->nullable()->after('backdrop_path');
            $table->decimal('rating', 3, 1)->nullable()->after('overview');
            $table->string('release_date')->nullable()->after('rating');
            $table->string('media_type')->nullable()->after('release_date'); // movie, tv, music
            $table->json('cast')->nullable()->after('media_type');
            $table->json('genres')->nullable()->after('cast');
            $table->boolean('metadata_fetched')->default(false)->after('genres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn([
                'poster_path',
                'backdrop_path',
                'overview',
                'rating',
                'release_date',
                'media_type',
                'cast',
                'genres',
                'metadata_fetched'
            ]);
        });
    }
};
