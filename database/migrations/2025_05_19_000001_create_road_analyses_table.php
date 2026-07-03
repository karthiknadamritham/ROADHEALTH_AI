<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('road_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('scan_id')->unique();           // e.g. #RH-2025-0001
            $table->string('image_path')->nullable();      // stored file path
            $table->string('original_filename')->nullable();
            $table->string('location')->default('Unknown Location');
            $table->string('condition');                   // Good / Fair / Poor / Excellent
            $table->unsignedTinyInteger('pci_score');      // 0–100
            $table->string('severity');                    // None / Low / Medium / High
            $table->text('recommended_action')->nullable();
            $table->unsignedTinyInteger('total_defects')->default(0);
            $table->json('detections')->nullable();        // JSON array of detections
            $table->string('api_mode')->default('demo');   // demo or live
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('road_analyses');
    }
};
