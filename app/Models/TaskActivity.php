<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'maintenance_task_id',
        'user_id',
        'action',
        'description',
        'image_path',
        'created_at',
    ];

    public function maintenanceTask()
    {
        return $this->belongsTo(MaintenanceTask::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
