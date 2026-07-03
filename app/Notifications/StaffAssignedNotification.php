<?php

namespace App\Notifications;

use App\Models\MaintenanceTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StaffAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(MaintenanceTask $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $analysis = $this->task->roadAnalysis;
        
        $title = 'New Task Assigned: ' . ucfirst($this->task->priority) . ' Priority';
        $desc = 'You have been assigned to repair ' . ($analysis->location ?? 'a reported location') . '. Scan ID: ' . ($analysis->scan_id ?? 'Unknown');

        // Choose severity class based on priority so the bell icon matches
        $severityClass = 'info';
        if ($this->task->priority === 'emergency' || $this->task->priority === 'high') {
            $severityClass = 'critical';
        } elseif ($this->task->priority === 'medium') {
            $severityClass = 'warning';
        }

        return [
            'task_id' => $this->task->id,
            'analysis_id' => $analysis->id,
            'title' => $title,
            'description' => $desc,
            'severity_class' => $severityClass,
        ];
    }
}
