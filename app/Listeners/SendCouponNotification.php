<?php

namespace App\Listeners;

use App\Events\CouponSent;
use App\Services\InAppNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCouponNotification implements ShouldQueue
{
    public function __construct(protected InAppNotificationService $notificationService) {}

    public function handle(CouponSent $event): void
    {
        $this->notificationService->send(
            $event->recipientUserId,
            'coupon',
            'New Discount Coupon',
            "You've received a {$event->percentage}% discount coupon: {$event->couponCode}",
            [
                'coupon_id' => $event->couponId,
                'coupon_code' => $event->couponCode,
                'percentage' => $event->percentage,
            ]
        );
    }
}