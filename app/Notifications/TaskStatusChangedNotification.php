<?php

namespace App\Notifications;

use App\Models\MaintenanceTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskStatusChangedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(MaintenanceTask $task, $action)
    {
        $this->task = $task;
        $this->action = $action;
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
        $title = 'Task Status Update: ' . ucfirst($this->action);
        $staffName = $this->task->assignedStaff ? $this->task->assignedStaff->name : 'Staff';
        $desc = "Staff member {$staffName} marked task at " . ($analysis->location ?? 'Unknown Location') . " as {$this->action}.";
        
        $severity = 'info';
        if ($this->action === 'completed') {
            $severity = 'success';
        } elseif ($this->action === 'paused') {
            $severity = 'warning';
        }

        return [
            'task_id' => $this->task->id,
            'analysis_id' => $analysis ? $analysis->id : null,
            'title' => $title,
            'description' => $desc,
            'severity_class' => $severity,
            'action' => $this->action,
        ];
    }
}
