<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ClientMessageToAdmin
{
    use Dispatchable;

    public function __construct(
        public int $chatId,
        public int $clientId,
        public string $messagePreview
    ) {}
}