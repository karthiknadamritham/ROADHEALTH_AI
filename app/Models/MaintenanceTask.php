<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    protected $fillable = [
        'road_analysis_id',
        'assigned_to',
        'assigned_by',
        'priority',
        'status',
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
    ];

    public function taskActivities()
    {
        return $this->hasMany(TaskActivity::class)->orderBy('created_at', 'asc');
    }

    public function roadAnalysis()
    {
        return $this->belongsTo(RoadAnalysis::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
