<?php

namespace App\Listeners;

use App\Events\ChatMessageSent;
use App\Services\InAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendChatMessageNotification implements ShouldQueue
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    public function handle(ChatMessageSent $event): void
    {
        $type = $event->claimId ? 'claim_message' : 'chat_message';

        $this->notificationService->send(
            $event->recipientUserId,
            $type,
            'New Message',
            $event->messagePreview ?: 'You have a new message',
            [
                'chat_id' => $event->chatId,
                'claim_id' => $event->claimId,
            ]
        );
    }
}