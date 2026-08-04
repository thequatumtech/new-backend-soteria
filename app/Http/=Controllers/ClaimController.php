<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimMessage;
use App\Models\ClaimStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $claims = Claim::all();
        return view('admin.claims.index', compact('claims'));
    }

    public function add_claim(Request $request,$id)
    {
//        TODO
        dd($request->all());
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
//        TODO
        $insurance_limit=10000;
        $net_premium=125;
        return view('admin.claims.view_claim', compact('claim','no_of_claims','insurance_limit','net_premium','attachments','claim_statuses'));
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
//        TODO send notifications
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
        $claim = Claim::find($request->claim_id);
        $claim_message = new ClaimMessage();
        $claim_message->claim_id = $claim->id;
        $claim_message->client_id = $claim->client_id;
        $claim_message->message = $request->message;
        $claim_message->is_message = '1';
        $claim_message->sent_by = '0';
        $claim_message->save();
        return response()->json([
            'success'=>'Message Sent Successfully',
            'status'=>200,
            'message'=>[
                'message' => $claim_message->message,
                'time' => $claim_message->created_at,
                'sent_by' => '0',
                'is_message' => '1'
            ]
        ]);
    }
}
