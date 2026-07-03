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
        // 1. Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('employee_id')->nullable()->after('phone');
            $table->string('department')->nullable()->after('employee_id');
            $table->string('territory')->nullable()->after('department');
            $table->string('zone')->nullable()->after('territory');
            $table->string('ward')->nullable()->after('zone');
            $table->string('area')->nullable()->after('ward');
            $table->string('status')->default('approved')->after('role'); // pending, approved, rejected
            $table->string('government_id_path')->nullable()->after('status');
            $table->string('profile_photo_path')->nullable()->after('government_id_path');
            $table->text('approval_remarks')->nullable()->after('profile_photo_path');
        });

        // 2. Update road_analyses table
        Schema::table('road_analyses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->string('territory')->nullable()->after('api_mode');
            $table->string('zone')->nullable()->after('territory');
            $table->string('ward')->nullable()->after('zone');
            $table->string('area')->nullable()->after('ward');
        });

        // 3. Update maintenance_tasks table
        Schema::table('maintenance_tasks', function (Blueprint $table) {
            $table->timestamp('deadline')->nullable()->after('priority');
            $table->string('before_image_path')->nullable()->after('status');
            $table->string('after_image_path')->nullable()->after('before_image_path');
            $table->text('repair_notes')->nullable()->after('after_image_path');
            $table->text('completion_report')->nullable()->after('repair_notes');
            $table->string('proof_document_path')->nullable()->after('completion_report');
            $table->timestamp('started_at')->nullable()->after('proof_document_path');
            $table->timestamp('paused_at')->nullable()->after('started_at');
            $table->timestamp('completed_at')->nullable()->after('paused_at');
            $table->text('officer_remarks')->nullable()->after('completed_at');
        });

        // 4. Create task_activities table
        Schema::create('task_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_task_id')->constrained('maintenance_tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // assigned, started, paused, completed, approved, correction
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_activities');

        Schema::table('maintenance_tasks', function (Blueprint $table) {
            $table->dropColumn([
                'deadline',
                'before_image_path',
                'after_image_path',
                'repair_notes',
                'completion_report',
                'proof_document_path',
                'started_at',
                'paused_at',
                'completed_at',
                'officer_remarks',
            ]);
        });

        Schema::table('road_analyses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'territory', 'zone', 'ward', 'area']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'employee_id',
                'department',
                'territory',
                'zone',
                'ward',
                'area',
                'status',
                'government_id_path',
                'profile_photo_path',
                'approval_remarks',
            ]);
        });
    }
};
