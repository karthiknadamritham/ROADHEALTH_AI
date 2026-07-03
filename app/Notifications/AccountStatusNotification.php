<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountStatusNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $remarks;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $remarks = '')
    {
        $this->status = $status;
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
        $title = $this->status === 'approved' ? 'Account Approved' : 'Account Rejected';
        $desc = $this->status === 'approved' 
            ? 'Your official registration has been approved. You now have access to your dashboard.' 
            : 'Your registration was rejected. Reason: ' . ($this->remarks ?: 'No details provided.');
        $severity = $this->status === 'approved' ? 'success' : 'critical';

        return [
            'title' => $title,
            'description' => $desc,
            'severity_class' => $severity,
            'status' => $this->status,
            'remarks' => $this->remarks,
        ];
    }
}
