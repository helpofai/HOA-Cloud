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
        Schema::create('abuse_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('share_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reported_url');
            $table->string('reporter_ip')->nullable();
            $table->string('reason');
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, action_taken, dismissed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abuse_reports');
    }
};
