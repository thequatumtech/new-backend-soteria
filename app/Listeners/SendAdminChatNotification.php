<?php

namespace App\Listeners;

use App\Events\ClientMessageToAdmin;
use App\Services\AdminNotificationService;
use App\Models\Client;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminChatNotification implements ShouldQueue
{
    public function __construct(protected AdminNotificationService $notificationService) {}

    public function handle(ClientMessageToAdmin $event): void
    {
        $client = Client::find($event->clientId);
        $clientName = $client->first_name ?? ('Client #' . $event->clientId);

        $this->notificationService->broadcastToSuperAdmins(
            'chat_message',
            'New Message from ' . $clientName,
            $event->messagePreview,
            [
                'chat_id' => $event->chatId,
                'client_id' => $event->clientId,
            ]
        );
    }
}