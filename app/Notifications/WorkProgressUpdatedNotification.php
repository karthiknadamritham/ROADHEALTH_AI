<?php

namespace App\Notifications;

use App\Models\MaintenanceTask;
use App\Models\TaskActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WorkProgressUpdatedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $activity;

    /**
     * Create a new notification instance.
     */
    public function __construct(MaintenanceTask $task, TaskActivity $activity)
    {
        $this->task = $task;
        $this->activity = $activity;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $analysis = $this->task->roadAnalysis;
        $title = 'Work Progress Update';
        $staffName = $this->task->assignedStaff ? $this->task->assignedStaff->name : 'Staff';
        $desc = "Staff member {$staffName} posted a progress update for road at " . ($analysis->location ?? 'Unknown Location') . ": \"" . \Str::limit($this->activity->description, 50) . "\"";

        return [
            'task_id' => $this->task->id,
            'analysis_id' => $analysis ? $analysis->id : null,
            'title' => $title,
            'description' => $desc,
            'severity_class' => 'info',
            'action' => 'progress_update',
            'image_path' => $this->activity->image_path,
        ];
    }
}
