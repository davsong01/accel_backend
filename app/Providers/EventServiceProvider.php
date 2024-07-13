<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\LeaveCreated;
use App\Listeners\SendLeaveCreatedNotification;
use App\Events\LeaveStatusUpdated;
use App\Events\Transaction\AccountBalanceUpdateEvent;
use App\Listeners\SendLeaveStatusUpdatedNotification;
use App\Listeners\Transaction\AccountBalanceUpdateListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LeaveCreated::class => [
            SendLeaveCreatedNotification::class,
        ],
        LeaveStatusUpdated::class => [
            SendLeaveStatusUpdatedNotification::class,
        ],
        AccountBalanceUpdateEvent::class => [
            AccountBalanceUpdateListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
