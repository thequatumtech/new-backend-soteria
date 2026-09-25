<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintEmail;
use App\Models\ComplaintStatus;
use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::with('line_of_business')->get();
        // dd($complaints);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function view_complaint(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        $attachments_str = $complaint->attachments;
        $attachments = [];
        if($attachments_str){
            $attachments = explode(',',$attachments_str);
        }
        return view('admin.complaints.view_complaint', compact('complaint','attachments'));
    }

    public function edit_complaint(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        $attachments_str = $complaint->attachments;
        $attachments = [];
        if($attachments_str){
            $attachments = explode(',',$attachments_str);
        }
        $insurance_companies = InsuranceCompany::all();
        $line_of_businesses = LineOfBusiness::all();
        $complaint_statuses = ComplaintStatus::all();
        return view('admin.complaints.edit_complaint', compact('complaint','attachments','insurance_companies','line_of_businesses','complaint_statuses'));
    }

    public function send_email(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        $complaint = Complaint::with([
            'client',
            'insurance_company',
        ])->findOrFail($request->complaint_id);

        $email = $complaint->client?->email_id;

        if (!$email) {
            return response()->json([
                'message' => 'Client email not found.'
            ], 422);
        }

        Mail::send([], [], function ($message) use ($email, $request) {

            $message->to($email)
                ->subject($request->subject)
                ->html($request->content);

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

    public function save_complaint(Request $request)
    {
        $complaint = Complaint::find($request->complaint_id);
        $old_status_id = $complaint->complaint_status_id;
        $complaint->insurance_company_id = $request->insurance_company_id;
        $complaint->line_of_business_id = $request->line_of_business_id;
        $complaint->complaint_status_id = $request->complaint_status_id;
        $complaint->complaint_status_id = $request->complaint_status_id;
        $complaint->complaint_log = $request->complaint_log;
        $attachments = [];
        if ($complaint->attachments) {
            $attachments = explode(',', $complaint->attachments);
        }
        $deleted_attachments = [];
        if($request->deleted_attachments) {
            $deleted_attachments = explode(',', $request->deleted_attachments);
            foreach ($deleted_attachments as $deleted_attachment) {
                if (file_exists(public_path('uploads/complaints/' . $complaint->id . '/') . $deleted_attachment)) {
                    unlink(public_path('uploads/complaints/' . $complaint->id . '/') . $deleted_attachment);
                }
            }
        }
        if ($attachments) {
            $attachments = array_diff($attachments, $deleted_attachments);
        }
        $new_attachments = [];
        if ($request->attachments) {
            foreach ($request->attachments as $uploadedFile) {
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $uploadedFile->move(public_path('uploads/complaints/' . $complaint->id . '/'), $filename);
                $new_attachments[] = $filename;
            }
        }
        $attachments_arr = array_merge($attachments,$new_attachments);
        $complaint->attachments = implode(',',$attachments_arr);
        $complaint->save();
        
        if ($old_status_id != $complaint->complaint_status_id) {
            event(new \App\Events\ComplaintStatusUpdated(
                recipientUserId: $complaint->client_id,
                complaintId: $complaint->id,
                status: $complaint->status->name
            ));
        }
        return redirect()->route('complaints')->with('success','Updated Successfully');
    }

    public function complaint_emails(Request $request)
    {
        $complaint_emails = ComplaintEmail::all();
        return view('admin.complaints.complaint_emails', compact('complaint_emails'));
    }

    public function complaint_emails_create(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::unique('complaint_emails'),
                ],
            ]);
            $complaint_email = new ComplaintEmail();
            $complaint_email->email = $request->email;
            $complaint_email->save();
            return redirect()->route('complaint_emails')->with('success', __('messages.complaints.add_complaint_emails_success'));
        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('complaint_emails')->with('error', __('messages.complaints.error'));
            // Handle errors here
        }
    }

    //update data
    public function complaint_emails_update(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    Rule::unique('complaint_emails')->ignore($request->cd_id),
                ],
            ]);

            $complaint_email = ComplaintEmail::findOrFail($request->cd_id);
            $complaint_email->email = $request->email;
            $complaint_email->save();
            return redirect()->route('complaint_emails')->with('success', __('messages.complaints.edit_complaint_emails_success'));
            // Validation passed, handle the validated data
            // You can access the validated data via $validatedData array

        } catch (ValidationException $e) {
            // Validation failed, handle the error
            $errors = $e->validator->errors()->all();
            return redirect()->route('complaint_emails')->with('error', __('messages.complaints.error'));
            // Handle errors here
        }
    }

    public function complaint_emails_destroy(Request $request)
    {
        $complaint_email = ComplaintEmail::findOrFail($request->cd_id);
        $complaint_email->delete();
        return redirect()->route('complaint_emails')->with('success', __('messages.complaints.delete_complaint_emails_success'));
    }
}
