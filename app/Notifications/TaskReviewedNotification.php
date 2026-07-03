<?php

namespace App\Notifications;

use App\Models\MaintenanceTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskReviewedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $result;
    protected $remarks;

    /**
     * Create a new notification instance.
     */
    public function __construct(MaintenanceTask $task, $result, $remarks = '')
    {
        $this->task = $task;
        $this->result = $result;
        $this->remarks = $remarks;
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
        $title = $this->result === 'approved' ? 'Task Approved' : 'Task Returned for Correction';
        $officerName = $this->task->assignedBy ? $this->task->assignedBy->name : 'Officer';
        
        if ($this->result === 'approved') {
            $desc = "Officer {$officerName} approved the repair at " . ($analysis->location ?? 'Unknown Location') . ". Task resolved.";
            $severity = 'success';
        } else {
            $desc = "Officer {$officerName} returned the task at " . ($analysis->location ?? 'Unknown Location') . " for correction. Remarks: " . ($this->remarks ?: 'None');
            $severity = 'critical';
        }

        return [
            'task_id' => $this->task->id,
            'analysis_id' => $analysis ? $analysis->id : null,
            'title' => $title,
            'description' => $desc,
            'severity_class' => $severity,
            'result' => $this->result,
            'remarks' => $this->remarks,
        ];
    }
}
