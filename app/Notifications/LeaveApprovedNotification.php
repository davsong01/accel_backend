<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveAndAbsenceManagement;

class LeaveApprovedNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(LeaveAndAbsenceManagement $data)
    {
        $this->data = $data;
        $this->data["user_type"] = "staff";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Leave Application Approved')
            ->line('Your leave application from ' . $this->data->start_date . ' to ' . $this->data->end_date . ' has been approved.')
            ->line('Thank you for your application.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "status" => "approved",
            "data" => $this->data,
        ];
    }
}
