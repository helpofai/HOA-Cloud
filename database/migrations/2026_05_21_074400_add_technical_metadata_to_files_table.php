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
            $table->decimal('duration', 12, 2)->nullable()->after('genres');
            $table->integer('width')->nullable()->after('duration');
            $table->integer('height')->nullable()->after('width');
            $table->string('codec')->nullable()->after('height');
            $table->json('technical_metadata')->nullable()->after('codec'); // ID3 tags, bitrates, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['duration', 'width', 'height', 'codec', 'technical_metadata']);
        });
    }
};
