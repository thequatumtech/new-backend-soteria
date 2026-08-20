<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class FirebaseChatService
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function pushMessage(int $chatId, array $data)
    {
        return $this->database
            ->getReference('chats/' . $chatId . '/messages')
            ->push($data);
    }

    // NEW — lightweight inbox summary push (for sidebar live updates)
    public function pushInboxUpdate(string $partyType, int $partyId, int $chatId, array $data)
    {
        $this->database
            ->getReference("inbox/{$partyType}/{$partyId}/{$chatId}")
            ->set($data);
    }
}