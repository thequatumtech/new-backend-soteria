<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ComplaintStatusUpdated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public int $recipientUserId,
        public int $complaintId,
        public string $status)
    {
        //
    }

}
