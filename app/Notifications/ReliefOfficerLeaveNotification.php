<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LeaveAndAbsenceManagement;

class ReliefOfficerLeaveNotification extends Notification
{
    use Queueable;

    public $data, $leave_owner;
    /**
     * Create a new notification instance.
     */

    public function __construct(LeaveAndAbsenceManagement $data)
    {
        $this->data = $data;
        $this->data["user_type"] = "staff";
        $this->leave_owner = $this->data->staff->user;
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
        $user_name = $this->leave_owner->first_name . " - " . $this->leave_owner->last_name;
        $recipient = $notifiable->first_name;
        $start_date = Carbon::createFromFormat('M jS, Y', $this->data->start_date);
        $end_date = Carbon::createFromFormat('M jS, Y', $this->data->end_date);

        return (new MailMessage)
            ->subject('New Leave Application')
            ->greeting('Hello ' . $recipient . ',')
            ->line('A new leave request has been submitted by ' . $user_name . '.')
            ->line('Leave Details:')
            ->line('Start Date: ' . $start_date->toDateString())
            ->line('End Date: ' . $end_date->toDateString())
            ->line('Please review and take the necessary actions.')
            ->action('View Leave Request', route('view_leave', $this->data->id))
            ->line('Thank you.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $user_name = $this->leave_owner->first_name . " - " . $this->leave_owner->last_name;
        $recipient = $notifiable->first_name . "" . $notifiable->last_name;
        $start_date = Carbon::createFromFormat('M jS, Y', $this->data->start_date);
        $end_date = Carbon::createFromFormat('M jS, Y', $this->data->end_date);

        return [
            'data' => $this->data
        ];
    }
}
