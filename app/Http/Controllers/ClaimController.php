<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimMessage;
use App\Models\ClaimStatus;
use App\Models\PurchasePolicy;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Chat;
use App\Services\FirebaseChatService;
use App\Models\Message;


class ClaimController extends Controller
{
    protected FirebaseChatService $firebase;

    public function __construct(FirebaseChatService $firebase)
    {
        $this->firebase = $firebase;
    }

    private function getPartyDisplay($chat, $senderType)
{
    if ($senderType === 'admin') {
        return [
            'id' => $chat->user_one_type === 'admin'
                ? $chat->user_one_id
                : $chat->user_two_id,

            'name' => 'Admin',
        ];
    }

    $clientId = $chat->user_one_type === 'client'
        ? $chat->user_one_id
        : $chat->user_two_id;

    $client = Client::find($clientId);

    return [
        'id' => $clientId,
        'name' => $client
            ? trim($client->first_name . ' ' . $client->last_name)
            : 'Client',
    ];
}

private function updateChatSummaryAndNotify(
    $chatId,
    $senderType,
    $messageText,
    $fileType = null,
    $fileName = null
) {
    $chat = Chat::find($chatId);

    if (!$chat) {
        return;
    }

    $preview = $messageText;

    if (!$preview && $fileType) {
        $preview = match ($fileType) {
            'image' => '📷 Photo',
            'video' => '🎥 Video',
            'pdf', 'doc' => '📄 ' . $fileName,
            default => '📎 Attachment',
        };
    }

    $receiverType = $chat->user_one_type === $senderType
        ? $chat->user_two_type
        : $chat->user_one_type;

    $receiverId = $chat->user_one_type === $senderType
        ? $chat->user_two_id
        : $chat->user_one_id;

    $chat->update([
        'last_message' => $preview,
        'last_message_at' => now(),
    ]);

    $liveUnreadCount = Message::where('chat_id', $chatId)
        ->where('sender_type', $senderType)
        ->where('is_read', false)
        ->count();

    $senderDisplay = $this->getPartyDisplay($chat, $senderType);

    $this->firebase->pushInboxUpdate(
        $receiverType,
        $receiverId,
        $chatId,
        [
            'chat_id' => $chatId,
            'party_id' => $senderDisplay['id'],
            'party_name' => $senderDisplay['name'],
            'last_message' => $preview,
            'last_message_at' => now()->toJSON(),
            'unread_count' => $liveUnreadCount,
        ]
    );
}

    public function index(Request $request)
    {
        $claims = Claim::all();

        // dd($claims);

        return view('admin.claims.index', compact('claims'));
    }

    public function add_claim(Request $request,$id)
    {

        dd($request->all());
    }


    public function send_notification(Request $request)
    {
        $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'type' => 'required|in:insurance_company,client',
        ]);

        $claim = Claim::with([
            'client',
            'insurance_company',
        ])->findOrFail($request->claim_id);

        $purchasepolicy = PurchasePolicy::findOrFail($claim->policy_id);

        $no_of_claims = Claim::where('client_id', $claim->client_id)->count();

        if ($request->type === 'insurance_company') {

            $email = $claim->insurance_company?->email;

            if (!$email) {
                return response()->json([
                    'message' => 'Insurance company email not found.'
                ], 422);
            }

            $subject = 'New Claim Notification – Claim No. ' . $claim->claim_no;
            $emailView = 'emails.claim_notification_company';

        } else {

            $email = $claim->client?->email_id;

            if (!$email) {
                return response()->json([
                    'message' => 'Client email not found.'
                ], 422);
            }

            $subject = 'Claim Submitted Successfully – Claim No. ' . $claim->claim_no;
            $emailView = 'emails.claim_notification_client';
        }

        Mail::send($emailView, [
            'claim' => $claim,
            'purchasepolicy' => $purchasepolicy,
            'no_of_claims' => $no_of_claims,
        ], function ($message) use ($email, $subject) {

            $message->to($email)
                ->subject($subject);

            $message->from(
                env('MAIL_USERNAME'),
                env('MAIL_FROM_NAME')
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully.'
        ]);
    }

    public function view_claim(Request $request,$id)
    {
        $claim = Claim::find($id);
        $client_id = $claim->client_id;
        $no_of_claims = Claim::where('client_id',$client_id)->count();
        $attachments_str = $claim->attachments;
        $attachments = [];
        if($attachments){
            $attachments = explode(",",$attachments_str);
        }
        $claim_statuses = ClaimStatus::all();

        $client = Client::findOrFail($claim->client_id);


        $purchasepolicy = PurchasePolicy::findOrFail($claim->policy_id);

        // dd($client);

        return view('admin.claims.view_claim', compact('claim','client','purchasepolicy','no_of_claims','attachments','claim_statuses'));
    }

    public function change_status(Request $request)
    {
        $claim = Claim::find($request->id);
        $old_status = $claim->status;
        $status = ClaimStatus::find($request->status);
        $claim->status = $status->name;
        $claim->save();
        $new_status = $claim->status;
        $message = 'Status changed from '.$old_status.' to '.$new_status;
        $claim_message = new ClaimMessage();
        $claim_message->claim_id = $claim->id;
        $claim_message->client_id = $claim->client_id;
        $claim_message->message = $message;
        $claim_message->is_message = '0';
        $claim_message->sent_by = '0';
        $claim_message->save();
        //by digvijay send notification to client when status changed
        event(new \App\Events\ClaimStatusUpdated(
        recipientUserId: $claim->client_id,
        claimId: $claim->id,
        claim_no: $claim->claim_no,
        status: $new_status
        ));
        //end notification
        
        return response()->json(['success'=>'Status changed successfully.']);
    }

    public function get_all_messages(Request $request)
    {
        $claim_message = ClaimMessage::where('claim_id',$request->claim_id)->get();
        return response()->json([
            'success' => 'Message Fetched Successfully',
            'status' => 200,
            'message' => [
                'messages' => $claim_message->count() == 0 ? [] : $claim_message,
                'client_name' => $claim_message[0]->client->first_name,
            ],
        ]);
    }
    public function send_message(Request $request)
    {
        $claim = Claim::findOrFail($request->claim_id);

        $claim_message = new ClaimMessage();
        $claim_message->claim_id = $claim->id;
        $claim_message->client_id = $claim->client_id;
        $claim_message->message = $request->message;
        $claim_message->is_message = '1';
        $claim_message->sent_by = '0';
        $claim_message->save();

        // Get the existing chat between admin and client
        $chat = Chat::where(function ($query) use ($claim) {
            $query->where('user_one_type', 'admin')
                ->where('user_one_id', auth('admin')->id())
                ->where('user_two_type', 'client')
                ->where('user_two_id', $claim->client_id);
        })->orWhere(function ($query) use ($claim) {
            $query->where('user_one_type', 'client')
                ->where('user_one_id', $claim->client_id)
                ->where('user_two_type', 'admin')
                ->where('user_two_id', auth('admin')->id());
        })->first();

        // Send to Firebase if chat exists
        if ($chat) {
            $this->firebase->pushMessage($chat->id, [
                'id' => $claim_message->id,
                'sender_id' => auth('admin')->id(),
                'sender_type' => 'admin',
                'message' => $claim_message->message,
                'file_path' => null,
                'file_type' => null,
                'file_name' => null,
                'is_read' => false,
                'created_at' => $claim_message->created_at->toJSON(),
            ]);

            $this->updateChatSummaryAndNotify(
                $chat->id,
                'admin',
                $claim_message->message,
                null,
                null
            );

            event(new \App\Events\ChatMessageSent(
                recipientUserId: $claim->client_id,
                chatId: $chat->id,
                claimId: $claim->id,
                messagePreview: $claim_message->message
            ));
        }

        return response()->json([
            'success' => 'Message Sent Successfully',
            'status' => 200,
            'message' => [
                'message' => $claim_message->message,
                'time' => $claim_message->created_at,
                'sent_by' => '0',
                'is_message' => '1'
            ]
        ]);
    }
}
