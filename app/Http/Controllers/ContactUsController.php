<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\ContactUsMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContactUsController extends Controller
{

    // public function index()
    // {
    //     $contactus = ContactUsMessage::select('client_id', DB::raw('MAX(created_at) as latest_message'))
    //         ->groupBy('client_id')
    //         ->orderBy('latest_message', 'desc')
    //         ->get();
    //     return view('admin.contactus.contactus',compact('contactus'));
    // }
        public function index()
        {
            $contactus = ContactUsMessage::select('contact_us_messages.client_id', DB::raw('MAX(contact_us_messages.created_at) as latest_message'))
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'contact_us_messages.client_id')
                        ->whereNull('clients.deleted_at');
                })
                ->groupBy('contact_us_messages.client_id')
                ->orderBy('latest_message', 'desc')
                ->get();

            return view('admin.contactus.contactus', compact('contactus'));
        }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'email' => 'required|email',
            'mobile' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        $validated['clientid'] = auth()->id();
        ContactUs::create($validated);

        return response()->json([
        'success' => true,
        'message' => 'Contact us has been Saved successfully!'
        ]);
    }

    public function edit($id)
    {
        $contact = ContactUs::findOrFail($id);

        return response()->json([
            'contact_name' => $contact->contact_name,
            'email' => $contact->email,
            'mobile' => $contact->mobile,
            'address' => $contact->address,
            'message' => $contact->message,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:100',
            'email' => 'required|email',
            'mobile' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        $validated['clientid'] = auth()->id();
        $contact = ContactUs::findOrFail($id);
        $contact->update($validated);

        return response()->json(['success' => true, 'message' => 'Contact updated successfully.']);
    }

    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();

        return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
    }


    public function indexContact()
    {
        $contactus=ContactUs::all();
        return view('admin.contactus.contact',compact('contactus'));
    }

    public function load_messages(Request $request)
    {
        $messages = ContactUsMessage::where('client_id',$request->client_id)->orderBy('created_at')->get();
        return response()->json([
            'success' => 'Messages Fetched Successfully',
            'status' => 200,
            'message' => [
                'messages' => $messages,
                'client_name' => $messages[0]->client->first_name,
                'file_path' => asset('uploads/contact_us/').'/'.$request->client_id.'/'
            ],
        ]);
    }

    public function send_message(Request $request)
    {
        $contact_us_message = new ContactUsMessage();
        $contact_us_message->client_id = $request->client_id;
        $contact_us_message->message = $request->message;
        $contact_us_message->is_message = '1';
        $contact_us_message->sent_by = '0';
        $contact_us_message->save();

        return response()->json([
            'success' => 'Message Sent Successfully',
            'status' => 200,
            'message' => [
                'contact_us_message' => $contact_us_message,
            ],
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


/*    public function index()
    {
        $contactUs = ContactUs::all();

        return view('content.contactUs.contactUs', ['contactUs' => $contactUs]);
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|numeric',
                'message' => 'required',
            ]);

            ContactUs::create([
                'mobile' => $request->input('mobile'),
                'message' => $request->input('message'),
            ]);

            return redirect()->route('pages.contact-us')->with('success', __('messages.contact_us.add_success'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();

            return redirect()->route('pages.contact-us')->with('error', $errors);
            // Handle errors here
       }
    }

    public function view($id)
    {
        $contactUs = ContactUs::find($id);

        return response()->json([
            'contactUs' => $contactUs,
            'status' => 200
       ]);
    }

    public function destroy(Request $request)
    {
        try{
            $request->validate([
                'delete_contact_us_id' => 'required|numeric|exists:contact_us,id',
            ]);

            ContactUs::where('id', $request->delete_contact_us_id)->delete();

            return redirect()->route('pages.contact-us')->with('success', __('messages.contact_us.delete_success'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->first();

            return redirect()->route('pages.contact-us')->with('error', $errors);
            // Handle errors here
       }
    }*/
}
