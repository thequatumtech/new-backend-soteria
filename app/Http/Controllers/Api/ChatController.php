<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Client;
use App\Models\Message;
use App\Services\FirebaseChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    protected FirebaseChatService $firebase;

    public function __construct(FirebaseChatService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function startChat(Request $request)
    {
        $request->validate([
            'receiver_id'   => 'required|integer',
            'receiver_type' => 'required|in:client,admin',
        ]);

        $userId   = $request->user_id;
        $userType = 'client';

        $chat = Chat::where(function ($q) use ($userId, $userType, $request) {
            $q->where('user_one_id', $userId)->where('user_one_type', $userType)
                ->where('user_two_id', $request->receiver_id)->where('user_two_type', $request->receiver_type);
        })->orWhere(function ($q) use ($userId, $userType, $request) {
            $q->where('user_one_id', $request->receiver_id)->where('user_one_type', $request->receiver_type)
                ->where('user_two_id', $userId)->where('user_two_type', $userType);
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_one_id'   => $userId,
                'user_one_type' => $userType,
                'user_two_id'   => $request->receiver_id,
                'user_two_type' => $request->receiver_type,
            ]);
        }

        return response()->json(['chat' => $chat]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required_without:file|nullable|string',
            'file'    => 'required_without:message|nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,pdf,doc,docx|max:20480',
        ]);

        $userId = $request->user_id;

        $filePath = $fileType = $fileName = $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $this->resolveFileType($file->getMimeType());
            $stored   = $file->store((string) $request->chat_id, 'chat_uploads');
            $filePath = '/uploads/chat_files/' . $stored;
        }

        $message = Message::create([
            'chat_id'     => $request->chat_id,
            'sender_id'   => $userId,
            'sender_type' => 'client',
            'message'     => $request->message,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
            'file_size'   => $fileSize,
        ]);

        $this->firebase->pushMessage($request->chat_id, [
            'id'          => $message->id,
            'sender_id'   => $userId,
            'sender_type' => 'client',
            'message'     => $request->message,
            'file_path'   => $filePath,
            'file_type'   => $fileType,
            'file_name'   => $fileName,
            'is_read'     => false,
            'created_at'  => $message->created_at->toJSON(),
        ]);

        $this->updateChatSummaryAndNotify($request->chat_id, 'client', $request->message, $fileType, $fileName);

        event(new \App\Events\ClientMessageToAdmin(
            chatId: $request->chat_id,
            clientId: $userId,
            messagePreview: $request->message ?: ($fileName ?? __('messages.api.sent_an_attachment'))
        ));

        return response()->json(['status' => true, 'data' => $message]);
    }

    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->paginate(30);

        return response()->json($messages);
    }

    // public function markAsRead($chatId)
    // {
    //     Message::where('chat_id', $chatId)->where('sender_type', 'admin')->update(['is_read' => true]);
    //     return response()->json(['status' => true]);
    // }
    public function markAsRead($chatId)
    {
        Message::where('chat_id', $chatId)->where('sender_type', 'admin')->update(['is_read' => true]);

        $chat = Chat::find($chatId);
        if ($chat) {
            $userId = request()->user_id;
            $this->firebase->pushInboxUpdate('client', $userId, $chatId, [
                'chat_id'         => $chat->id,
                'party_id'        => $chat->user_one_type === 'admin' ? $chat->user_one_id : $chat->user_two_id,
                'party_name'      => $this->getPartyDisplay($chat, 'admin')['name'],
                'last_message'    => $chat->last_message,
                'last_message_at' => $chat->last_message_at ? $chat->last_message_at->toJSON() : null,
                'unread_count'    => 0,
            ]);
        }

        return response()->json(['status' => true]);
    }

    public function chatList(Request $request)
    {
        $userId = $request->user_id;

        $chats = Chat::where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)->where('user_one_type', 'client');
        })->orWhere(function ($q) use ($userId) {
            $q->where('user_two_id', $userId)->where('user_two_type', 'client');
        })
            ->withCount(['messages as live_unread_count' => function ($q) {
                $q->where('sender_type', 'admin')->where('is_read', false);
            }])
            ->orderByRaw('last_message_at IS NULL, last_message_at DESC')
            ->paginate(30);

        return response()->json($chats);
    }

    private function resolveFileType($mime)
    {
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'video/')) return 'video';
        if ($mime === 'application/pdf') return 'pdf';
        if (in_array($mime, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) return 'doc';
        return 'other';
    }

    private function getPartyDisplay($chat, $type)
    {
        if ($type === 'client') {
            $id = $chat->user_one_type === 'client' ? $chat->user_one_id : $chat->user_two_id;
            $client = Client::find($id);
            return ['id' => $id, 'name' => $client->first_name ?? ('Client #' . $id)];
        }

        $id = $chat->user_one_type === 'admin' ? $chat->user_one_id : $chat->user_two_id;
        $admin = \App\Models\Admin::find($id);
        return ['id' => $id, 'name' => $admin->full_name ?? 'Support Admin'];
    }

    private function updateChatSummaryAndNotify($chatId, $senderType, $messageText, $fileType, $fileName)
    {
        $chat = Chat::find($chatId);

        $preview = $messageText;
        if (!$preview && $fileType) {
            $preview = match ($fileType) {
                'image'      => __('messages.api.photo_attachment'),
                'video'      => __('messages.api.video_attachment'),
                'pdf', 'doc' => __('messages.api.document_attachment') . $fileName,
                default      => __('messages.api.file_attachment'),
            };
        }

        $receiverType = $chat->user_one_type === $senderType ? $chat->user_two_type : $chat->user_one_type;
        $receiverId   = $chat->user_one_type === $senderType ? $chat->user_two_id : $chat->user_one_id;

        $chat->update([
            'last_message'    => $preview,
            'last_message_at' => now(),
        ]);

        // Live count — NOT stored counter, avoids race conditions
        $liveUnreadCount = Message::where('chat_id', $chatId)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->count();

        $senderDisplay = $this->getPartyDisplay($chat, $senderType);

        $this->firebase->pushInboxUpdate($receiverType, $receiverId, $chatId, [
            'chat_id'         => $chatId,
            'party_id'        => $senderDisplay['id'],
            'party_name'      => $senderDisplay['name'],
            'last_message'    => $preview,
            'last_message_at' => now()->toJSON(),
            'unread_count'    => $liveUnreadCount,
        ]);
    }
}
