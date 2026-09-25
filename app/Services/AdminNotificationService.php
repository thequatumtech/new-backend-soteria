<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminNotification;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;

class AdminNotificationService
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function broadcastToSuperAdmins(string $type, string $title, string $body, array $data = []): void
    {
        $superAdminIds = Admin::where('is_super_admin', 1)->pluck('id');

        foreach ($superAdminIds as $adminId) {
            $this->send($adminId, $type, $title, $body, $data);
        }
    }

    public function send(int $adminId, string $type, string $title, string $body, array $data = []): AdminNotification
    {
        $notification = AdminNotification::create([
            'admin_id' => $adminId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        $this->pushToRealtimeDb($adminId, $notification);

        return $notification;
    }

    protected function pushToRealtimeDb(int $adminId, AdminNotification $notification): void
    {
        try {
            $this->database
                ->getReference("admin_notifications/{$adminId}/{$notification->id}")
                ->set([
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'data' => $notification->data,
                    'read' => false,
                    'created_at' => $notification->created_at->timestamp,
                ]);
        } catch (\Throwable $e) {
            Log::warning('RTDB admin notification push failed', [
                'admin_id' => $adminId,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function markAsRead(int $adminId, int $notificationId): void
    {
        $notification = AdminNotification::where('id', $notificationId)
            ->where('admin_id', $adminId)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        try {
            $this->database
                ->getReference("admin_notifications/{$adminId}/{$notificationId}/read")
                ->set(true);
        } catch (\Throwable $e) {
            Log::warning('RTDB admin mark-as-read failed', [
                'admin_id' => $adminId,
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}