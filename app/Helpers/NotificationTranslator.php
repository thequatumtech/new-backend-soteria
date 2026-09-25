<?php

namespace App\Services;

use App\Models\Notification;

class NotificationTranslator
{
    public static function apply(Notification $notification, string $lang): Notification
    {
        $key = "messages.notifications.{$notification->type}";

        if ($lang === 'en' || !trans()->has($key, $lang)) {
            return $notification;
        }

        $data = $notification->data ?? [];
        $params = [
            'status'     => $data['status'] ?? '',
            'percentage' => $data['percentage'] ?? '',
            'code'       => $data['coupon_code'] ?? '',
            'days'       => $data['days'] ?? '',
        ];

        $notification->title = trans("{$key}.title", $params, $lang);
        $notification->body  = trans("{$key}.body", $params, $lang);

        return $notification;
    }
}