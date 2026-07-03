<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification
{
    use Queueable;

    protected $contactMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
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
        $senderDesc = $this->contactMessage->type === 'officer_to_officer'
            ? 'Officer from ' . ($this->contactMessage->sender_territory ?? 'another territory')
            : 'Citizen ' . $this->contactMessage->name;

        $title = 'New Contact Message: ' . $this->contactMessage->subject;
        $desc = "Received an inquiry from {$senderDesc}. Target territory: {$this->contactMessage->territory}.";

        return [
            'contact_message_id' => $this->contactMessage->id,
            'title' => $title,
            'description' => $desc,
            'severity_class' => 'info',
        ];
    }
}
