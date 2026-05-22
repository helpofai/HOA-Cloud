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
        Schema::create('media_processes', function (Blueprint $row) {
            $row->id();
            $row->foreignId('user_id')->constrained()->onDelete('cascade');
            $row->foreignId('file_id')->nullable()->constrained()->onDelete('cascade');
            $row->string('type'); // transcode, hls, watermark, technical_metadata
            $row->string('status')->default('pending'); // pending, processing, completed, failed
            $row->decimal('progress', 5, 2)->default(0);
            $row->integer('pid')->nullable();
            $row->string('command')->nullable();
            $row->string('output_path')->nullable();
            $row->text('error')->nullable();
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_processes');
    }
};
