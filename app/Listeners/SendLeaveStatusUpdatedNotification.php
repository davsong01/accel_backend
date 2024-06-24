<?php

namespace App\Listeners;

use App\Events\LeaveStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\LeaveApprovedNotification;
use App\Notifications\LeaveRejectedNotification;

class SendLeaveStatusUpdatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeaveStatusUpdated $event): void
    {
        $leave = $event->leave;
        $status = $event->status;
        \Log::info('Leave status updated:', [
            'leave_id' => $event->leave,
            'status' => $event->leave->status,
        ]);
        // Notify the user who applied for leave
        if ($status === 'approved') {
            $leave->staff->user->notify(new LeaveApprovedNotification($leave));
        } elseif ($status === 'rejected') {
            $leave->staff->user->notify(new LeaveRejectedNotification($leave));
        }
    }
}
