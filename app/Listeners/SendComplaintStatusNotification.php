<?php

namespace App\Listeners;

use App\Events\ComplaintStatusUpdated;
use App\Services\InAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendComplaintStatusNotification implements ShouldQueue
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    public function handle(ComplaintStatusUpdated $event): void
    {
        $this->notificationService->send(
            $event->recipientUserId,
            'complaint_status',
            'Complaint Status Updated',
            "Your complaint status has been updated to \"{$event->status}\"",
            [
                'complaint_id' => $event->complaintId,
                'status' => $event->status,
            ]
        );
    }
}