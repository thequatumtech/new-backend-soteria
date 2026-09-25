<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class CouponSent
{
    use Dispatchable;

    public function __construct(
        public int $recipientUserId,
        public int $couponId,
        public string $couponCode,
        public float $percentage
    ) {}
}