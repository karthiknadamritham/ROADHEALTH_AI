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
        Schema::table('road_analyses', function (Blueprint $table) {
            $table->boolean('is_registered')->default(false)->after('longitude');
            $table->string('title')->nullable()->after('is_registered');
            $table->string('landmark')->nullable()->after('title');
            $table->text('remarks')->nullable()->after('landmark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('road_analyses', function (Blueprint $table) {
            $table->dropColumn(['is_registered', 'title', 'landmark', 'remarks']);
        });
    }
};
