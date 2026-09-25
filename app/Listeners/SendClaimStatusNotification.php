<?php

namespace App\Listeners;

use App\Events\ClaimStatusUpdated;
use App\Services\InAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendClaimStatusNotification implements ShouldQueue
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    public function handle(ClaimStatusUpdated $event): void
    {
        $this->notificationService->send(
            $event->recipientUserId,
            'claim_status',
            'Claim Status Updated',
            "Your claim no. \"{$event->claim_no}\" status has been updated to \"{$event->status}\"",
            [
                'claim_id' => $event->claimId,
                'status' => $event->status,
            ]
        );
    }
}