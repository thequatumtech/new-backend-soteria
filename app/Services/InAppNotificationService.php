<?php

namespace App\Services;

use App\Models\Notification;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;

class InAppNotificationService
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function send(int $clientId, string $type, string $title, string $body, array $data = []): Notification
    {
        $notification = Notification::create([
            'client_id' => $clientId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        $this->pushToRealtimeDb($clientId, $notification);

        return $notification;
    }

    // protected function pushToRealtimeDb(int $clientId, Notification $notification): void
    // {
    //     try {
    //         $this->database
    //             ->getReference("notifications/{$clientId}/{$notification->id}")
    //             ->set([
    //                 'id' => $notification->id,
    //                 'type' => $notification->type,
    //                 'title' => $notification->title,
    //                 'body' => $notification->body,
    //                 'data' => $notification->data,
    //                 'read' => false,
    //                 'created_at' => $notification->created_at->timestamp,
    //             ]);
    //     } catch (\Throwable $e) {
    //         Log::warning('RTDB notification push failed', [
    //             'client_id' => $clientId,
    //             'notification_id' => $notification->id,
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }
    
    protected function pushToRealtimeDb(int $clientId, Notification $notification): void
    {
        $client = \App\Models\Client::find($clientId);
        $lang = $client->language ?? 'en';
    
        $translated = \App\Services\NotificationTranslator::apply(clone $notification, $lang);
    
        try {
            $this->database
                ->getReference("notifications/{$clientId}/{$notification->id}")
                ->set([
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $translated->title,
                    'body' => $translated->body,
                    'data' => $notification->data,
                    'read' => false,
                    'created_at' => $notification->created_at->timestamp,
                ]);
        } catch (\Throwable $e) {
            Log::warning('RTDB notification push failed', [
                'client_id' => $clientId,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function markAsRead(int $clientId, int $notificationId): void
    {
        $notification = Notification::where('id', $notificationId)
            ->where('client_id', $clientId)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        try {
            $this->database
                ->getReference("notifications/{$clientId}/{$notificationId}/read")
                ->set(true);
        } catch (\Throwable $e) {
            Log::warning('RTDB mark-as-read failed', [
                'client_id' => $clientId,
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}