<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveAndAbsenceManagement;

class LeaveStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leave;
    public $status;
    /**
     * Create a new event instance.
     */
    public function __construct(LeaveAndAbsenceManagement $leave, $status)
    {
        $leave->load(['staff', 'staff.user']);
        $this->leave = $leave;
        $this->status = $status; // Status can be 'approved' or 'rejected'
    }
}
