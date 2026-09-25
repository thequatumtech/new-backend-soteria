<?php

namespace App\Listeners;

use App\Events\ExpiryReminderDue;
use App\Services\InAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExpiryReminderNotification implements ShouldQueue
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    public function handle(ExpiryReminderDue $event): void
    {
        $this->notificationService->send(
            $event->recipientUserId,
            'expiry_reminder',
            'Insurance Plan Expiring Soon',
            "Your insurance plan is expiring in {$event->daysLeft} days",
            [
                'plan_id' => $event->policyId,
                'expiry_date' => $event->expiryDate,
                'days' => $event->daysLeft,
            ]
        );
    }
}