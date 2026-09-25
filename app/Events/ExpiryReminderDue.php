<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ExpiryReminderDue
{
    use Dispatchable;

    public function __construct(
        public int $recipientUserId,
        public int $policyId,
        public string $expiryDate,
        public int $daysLeft
    ) {}
}