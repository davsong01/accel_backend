<?php

namespace App\Listeners;

use App\Events\LeaveCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ReliefOfficerLeaveNotification;

class SendLeaveCreatedNotification implements ShouldQueue
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
    public function handle(LeaveCreated $event): void
    {
        // We could check for different user role if need be in the future

        $reliefOfficer = $event->leave->reliefOfficer->user;
        $reliefOfficer->notify(new ReliefOfficerLeaveNotification($event->leave));
    }
}
