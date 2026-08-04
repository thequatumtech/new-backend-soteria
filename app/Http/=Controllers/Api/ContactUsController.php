<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\ContactUsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContactUsController extends Controller
{
    // public function contactChatsList(Request $request)
    // {
    //     try
    //     {
    //         $messages = ContactUsMessage::where('client_id',$request->user_id)->orderBy('created_at')->get();
    //         if ($messages!='[]') {
    //             return response()->json([
    //                 'status' => true, 
    //                 'status_code' => 200,
    //                 'message' => 'Messages Fetched Successfully',
    //                 'data' => [
    //                     'messages' => $messages,
    //                     'client_name' => $messages[0]->client->first_name,
    //                     'file_path' => asset('uploads/contact_us/').'/'.$request->user_id.'/'
    //                 ],
    //             ]);   
    //         }
    //         return response()->json([
    //                 'status' => true, 
    //                 'status_code' => 200,
    //                 'message' => 'Messages Fetched Successfully',
    //                 'data' => array()
    //             ]);
    //     }
    //     catch(\Exception $e)
    //     {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
    //     }
    // }
     public function contactChatsList(Request $request)
    {

         if($request->id)
            {
                 try
                {
                    $contact_us = ContactUs::where('clientid', $request->id)->get();
                    if ($contact_us != '[]') {
                        return response()->json([
                            'status' => true, 
                            'status_code' => 200,
                            'message' => 'Contact Us Fetched Successfully',
                            'data' => $contact_us,
                        ]);
                    }
                    return response()->json([
                        'status' => true, 
                        'status_code' => 200,
                        'message' => 'Contact Us Fetched Successfully',
                        'data' => array()
                    ]);
                }
                catch(\Exception $e)
                {
                    return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
                }
            }
            else{
                try
                {       
                    $messages = ContactUsMessage::where('client_id',$request->user_id)->orderBy('created_at')->get();
                    if ($messages!='[]') {
                        return response()->json([
                            'status' => true, 
                            'status_code' => 200,
                            'message' => 'Messages Fetched Successfully',
                            'data' => [
                                'messages' => $messages,
                                'client_name' => $messages[0]->client->first_name,
                                'file_path' => asset('uploads/contact_us/').'/'.$request->user_id.'/'
                            ],
                        ]);   
                    }
                    return response()->json([
                            'status' => true, 
                            'status_code' => 200,
                            'message' => 'Messages Fetched Successfully',
                            'data' => array()
                        ]);
                }
                catch(\Exception $e)
                {
                    return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
                }
            }
    }


    public function sendCantactMessage(Request $request)
    {
        if ($request->hasFile('file')) 
        {
            $client_id = $request->user_id;
            $uploadedFile = $request->file('file');
            $filename = $uploadedFile->getClientOriginalName(); // Original filename
            $uploadedFile->move(public_path('uploads/contact_us/'.$client_id.'/'), $filename);
            $contact_us_message = new ContactUsMessage();
            $contact_us_message->client_id = $client_id;
            $contact_us_message->message = $filename;
            $contact_us_message->is_message = '0';
            $contact_us_message->sent_by = '1';
            $contact_us_message->save();
        }
        else
        {
            $contact_us_message = new ContactUsMessage();
            $contact_us_message->client_id = $request->user_id;
            $contact_us_message->message = $request->message;
            $contact_us_message->is_message = '1';
            $contact_us_message->sent_by = '1';
            $contact_us_message->save();
        }

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'Message Sent Successfully',
            'data' => $contact_us_message
        ]);
    }

    public function upload_file(Request $request)
    {
        if ($request->hasFile('file')) {
            $client_id = $request->client_id;
            $uploadedFile = $request->file('file');
            $filename = $uploadedFile->getClientOriginalName(); // Original filename
            $uploadedFile->move(public_path('uploads/contact_us/'.$client_id.'/'), $filename);
            $contact_us_message = new ContactUsMessage();
            $contact_us_message->client_id = $client_id;
            $contact_us_message->message = $filename;
            $contact_us_message->is_message = '0';
            $contact_us_message->sent_by = '0';
            $contact_us_message->save();
        }
        return response()->json([
            'success' => 'File Uploaded Successfully',
            'status' => 200,
            'message' => [
                'contact_us_message' => $contact_us_message,
                'file_path' => asset('uploads/contact_us/').'/'.$client_id.'/'
            ],
        ]);
    }
}
