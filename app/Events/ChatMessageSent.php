<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ChatMessageSent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public int $recipientUserId,
        public int $chatId,
        public ?int $claimId = null, // null if it's a normal chat, set if claim-related
        public string $messagePreview = '')
    {
        //
    }

}
