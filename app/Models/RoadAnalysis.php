<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadAnalysis extends Model
{
    protected $fillable = [
        'scan_id',
        'image_path',
        'original_filename',
        'location',
        'condition',
        'pci_score',
        'severity',
        'recommended_action',
        'total_defects',
        'detections',
        'api_mode',
        'latitude',
        'longitude',
        'is_registered',
        'title',
        'landmark',
        'remarks',
        'user_id',
        'territory',
        'zone',
        'ward',
        'area',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceTask()
    {
        return $this->hasOne(MaintenanceTask::class);
    }

    protected $casts = [
        'detections'    => 'array',
        'pci_score'     => 'integer',
        'is_registered' => 'boolean',
    ];

    /**
     * Get the badge CSS class for condition display.
     */
    public function getConditionBadgeClassAttribute(): string
    {
        return match(strtolower($this->condition)) {
            'poor'      => 'badge-poor',
            'fair'      => 'badge-fair',
            'good'      => 'badge-good',
            'excellent' => 'badge-excellent',
            'invalid'   => 'badge-invalid',
            default     => 'badge-fair',
        };
    }

    /**
     * Get the color for PCI score gauge.
     */
    public function getPciColorAttribute(): string
    {
        if (strtolower($this->condition) === 'invalid') return '#6b7280'; // gray
        if ($this->pci_score >= 80) return '#10b981'; // green
        if ($this->pci_score >= 55) return '#f59e0b'; // amber
        return '#ef4444';                              // red
    }
}
