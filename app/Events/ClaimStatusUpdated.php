<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ClaimStatusUpdated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public int $recipientUserId,
        public int $claimId,
        public string $claim_no,
        public string $status)
    {
        //
    }
}
