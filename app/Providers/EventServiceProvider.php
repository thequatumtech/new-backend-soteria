<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\ChatMessageSent::class => [
            \App\Listeners\SendChatMessageNotification::class,
        ],
        \App\Events\ClaimStatusUpdated::class => [
            \App\Listeners\SendClaimStatusNotification::class,
        ],
        \App\Events\ComplaintStatusUpdated::class => [
            \App\Listeners\SendComplaintStatusNotification::class,
        ],
        \App\Events\CouponSent::class => [
            \App\Listeners\SendCouponNotification::class,
        ],
        \App\Events\ExpiryReminderDue::class => [
            \App\Listeners\SendExpiryReminderNotification::class,
        ],
        //for admin notification when client send message to admin
        \App\Events\ClientMessageToAdmin::class => [
            \App\Listeners\SendAdminChatNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
