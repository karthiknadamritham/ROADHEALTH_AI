<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\RoadAnalysis;

class NewRoadAnalysisNotification extends Notification
{
    use Queueable;

    protected $analysis;

    /**
     * Create a new notification instance.
     */
    public function __construct(RoadAnalysis $analysis)
    {
        $this->analysis = $analysis;
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
        $severityClass = 'info';
        $title = 'New Road Analysis Report';
        $desc = "A new road analysis report was submitted for {$this->analysis->location}.";
        
        if ($this->analysis->pci_score < 35) {
            $severityClass = 'critical';
            $title = 'Critical Road Issue Detected';
            $desc = "Severe damage detected at {$this->analysis->location} with a PCI score of {$this->analysis->pci_score}. Immediate attention required.";
        } elseif ($this->analysis->pci_score < 55) {
            $severityClass = 'warning';
            $title = 'Poor Road Condition';
            $desc = "A poor road condition was reported at {$this->analysis->location}. Maintenance may be needed soon.";
        } elseif ($this->analysis->pci_score < 75) {
            $severityClass = 'warning'; // Using warning for fair as well to get orange color
        } else {
            $severityClass = 'success';
        }

        return [
            'analysis_id' => $this->analysis->id,
            'scan_id' => $this->analysis->scan_id,
            'location' => $this->analysis->location,
            'pci_score' => $this->analysis->pci_score,
            'severity_class' => $severityClass,
            'title' => $title,
            'description' => $desc,
        ];
    }
}
