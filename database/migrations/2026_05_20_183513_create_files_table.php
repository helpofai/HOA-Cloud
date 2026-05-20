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
        Schema::create('files', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->uuid('uuid')->unique();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('folder_id')->nullable()->constrained()->onDelete('cascade');
            
            $blueprint->string('name'); // User-visible filename
            $blueprint->string('original_name'); // Original name on upload
            $blueprint->string('disk_name'); // Obfuscated random SHA256/UUID hash
            $blueprint->string('extension')->nullable();
            $blueprint->string('mime_type')->nullable();
            $blueprint->bigInteger('size')->default(0);
            
            $blueprint->string('disk')->default('local'); // s3, wasabi, local, etc.
            $blueprint->string('hash')->index(); // SHA256 for deduplication logic
            
            $blueprint->timestamps();

            $blueprint->index(['user_id', 'folder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
