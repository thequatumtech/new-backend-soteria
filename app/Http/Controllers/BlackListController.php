<?php

namespace App\Http\Controllers;

use App\Exports\BlackListExport;
use App\Mail\SendBlackListClientInfo;
use App\Models\BlackListDetail;
use App\Models\Client;
use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class BlackListController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::where('is_blacklisted',1)->get();
        return view('admin.black_list.client',compact('clients'));
    }

    public function restore(Request $request)
    {
        $selected_client_id = explode(',',$request->remove_from_blacklist_client_id);
        foreach ($selected_client_id as $client_id) {
            $client = Client::find($client_id);
            $client->is_blacklisted = 2;
            $client->black_list_reason = null;
            $client->save();
            BlackListDetail::where('client_id',$client->id)->delete();
        }
        return redirect()->route('black_list')->with('success',__('messages.black_lists.reinstate_success'));
    }

    public function send_blacklist_email(Request $request)
    {
        $email = $request->admin_email;
        $query = $request->search_query;
        $clients = Client::where('is_blacklisted', 1)
            ->where(function ($qry) use ($query) {
                if ($query) {
                    $qry->where('first_name', 'like', '%' . $query . '%')
                        ->orWhere('father_name', 'like', '%' . $query . '%')
                        ->orWhere('grandfather_name', 'like', '%' . $query . '%')
                        ->orWhere('surname', 'like', '%' . $query . '%')
                        ->orWhere('national_id_number', 'like', '%' . $query . '%')
                        ->orWhere('mobile_no', 'like', '%' . $query . '%');
                }
            })
            ->get();
        $clients_arr = [];
        $count = 0;
        foreach ($clients as $client) {
            $count++;
            $clients_arr[] = [
                $count,
                $client->full_name,
                $client->national_id_number,
                $client->mobile_no,
                $client->black_list_reason
            ];
        }
        $export = new BlackListExport($clients_arr);
        Excel::store($export,'uploads/black_list/blacklist.xlsx','excel_uploads');
        Mail::send('emails.send_black_list', [], function ($message) use ($email) {
            $message->to($email)
                ->subject(__('messages.black_lists.blacklisted_clients_list'));
            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            $message->attach(public_path('uploads/black_list/blacklist.xlsx'));
        });
        return back()->with('success',__('messages.black_lists.email_sent'));
    }

    public function client($id)
    {
        $client = Client::find($id);
        $insurance_companies = $client->black_list_detail->dealt_insurance_companies_id;
        $insurance_companies_arr = explode(',', $insurance_companies);
        $insurance_companies_name_arr = [];
        foreach ($insurance_companies_arr as $insurance_company_id) {
            $insurance_company = InsuranceCompany::find($insurance_company_id);
            if($insurance_company){
                $insurance_companies_name_arr[] = $insurance_company->company_name;
            }
        }
        $insurance_companies_name = implode(', ', $insurance_companies_name_arr);
        $insurance_types = $client->black_list_detail->purchased_insurance_types_id;
        $insurance_types_arr = explode(',', $insurance_types);
        $insurance_types_name_arr = [];
        foreach ($insurance_types_arr as $insurance_type_id) {
            $insurance_type = LineOfBusiness::find($insurance_type_id);
            if($insurance_type){
                $insurance_types_name_arr[] = $insurance_type->name;
            }
        }
        $insurance_types_name = implode(', ', $insurance_types_name_arr);
        $first_insurance_date = $client->black_list_detail->first_insurance_date;
        if($first_insurance_date){
            $insured_period_months = Carbon::parse($first_insurance_date)->diffInMonths(Carbon::now());
            if($insured_period_months <= 12){
                $insured_period = $insured_period_months . ' months';
            } else {
                $insured_period = floor($insured_period_months/12) . ' years';
            }
        } else {
            $insured_period = '-';
        }

        $insurance_types = $client->black_list_detail->blocked_insurance_types_id;
        $insurance_types_arr = explode(',', $insurance_types);
        $insurance_types_name_arr = [];
        foreach ($insurance_types_arr as $insurance_type_id) {
            $insurance_type = LineOfBusiness::find($insurance_type_id);
            if($insurance_type){
                $insurance_types_name_arr[] = $insurance_type->name;
            }
        }
        $insurance_type_cannot_purchase = implode(', ', $insurance_types_name_arr);
        $attachments = explode(',',$client->black_list_detail->attachments);
        return view('admin.black_list.client-detail',compact('client','insurance_companies_name','insurance_types_name','insurance_types','first_insurance_date','insured_period','insurance_type_cannot_purchase','attachments'));
    }

    public function client_edit(Request $request, $id)
    {
        $client = Client::find($id);
        $insurance_companies = $client->black_list_detail->dealt_insurance_companies_id;
        $insurance_companies_arr = explode(',', $insurance_companies);
        $insurance_companies_name_arr = [];
        foreach ($insurance_companies_arr as $insurance_company_id) {
            $insurance_company = InsuranceCompany::find($insurance_company_id);
            if($insurance_company){
                $insurance_companies_name_arr[] = $insurance_company->company_name;
            }
        }
        $insurance_companies_name = implode(', ', $insurance_companies_name_arr);
        $insurance_types = $client->black_list_detail->purchased_insurance_types_id;
        $insurance_types_arr = explode(',', $insurance_types);
        $insurance_types_name_arr = [];
        foreach ($insurance_types_arr as $insurance_type_id) {
            $insurance_type = LineOfBusiness::find($insurance_type_id);
            if($insurance_type){
                $insurance_types_name_arr[] = $insurance_type->name;
            }
        }
        $insurance_types_name = implode(', ', $insurance_types_name_arr);
        $first_insurance_date = $client->black_list_detail->first_insurance_date;
        if($first_insurance_date){
            $insured_period_months = Carbon::parse($first_insurance_date)->diffInMonths(Carbon::now());
            if($insured_period_months <= 12){
                $insured_period = $insured_period_months . ' months';
            } else {
                $insured_period = floor($insured_period_months/12) . ' years';
            }
        } else {
            $insured_period = '-';
        }

        $blocked_insurance_types_id = $client->black_list_detail->blocked_insurance_types_id;
        $blocked_insurance_types_arr = explode(',', $blocked_insurance_types_id);
        $attachments = [];
        if($client->black_list_detail->attachments) {
            $attachments = explode(',', $client->black_list_detail->attachments);
        }
        $line_of_business = LineOfBusiness::all();
        return view('admin.black_list.client-edit',compact('client','line_of_business','insurance_companies_name','insurance_types_name','insurance_types','first_insurance_date','insured_period','blocked_insurance_types_arr','attachments'));
    }

    public function client_save(Request $request)
    {
        $client = Client::find($request->client_id);
        $client->black_list_reason = $request->black_list_reason;
        $client->save();
        $black_list_details = BlackListDetail::where('client_id', $client->id)->first();
        $black_list_details->blocked_insurance_types_id = isset($request->line_of_business)?implode(',', $request->line_of_business):null;
        $attachments = [];
        if ($black_list_details->attachments) {
            $attachments = explode(',', $client->black_list_detail->attachments);
        }
        $deleted_attachments = [];
        if($request->deleted_attachments) {
            $deleted_attachments = explode(',', $request->deleted_attachments);
            foreach ($deleted_attachments as $deleted_attachment) {
                if (file_exists(public_path('uploads/black_list/' . $client->id . '/') . $deleted_attachment)) {
                    unlink(public_path('uploads/black_list/' . $client->id . '/') . $deleted_attachment);
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
                $uploadedFile->move(public_path('uploads/black_list/' . $client->id . '/'), $filename);
                $new_attachments[] = $filename;
            }
        }
        $attachments_arr = array_merge($attachments,$new_attachments);
        $black_list_details->attachments = implode(',',$attachments_arr);
        $black_list_details->save();
        return redirect()->route('black_list')->with('success','Updated Successfully');
    }

    public function send_client_info_email(Request $request)
    {
        $client = Client::find($request->client_id);
        $insurance_companies = $client->black_list_detail->dealt_insurance_companies_id;
        $insurance_companies_arr = explode(',', $insurance_companies);
        $insurance_companies_name_arr = [];
        foreach ($insurance_companies_arr as $insurance_company_id) {
            $insurance_company = InsuranceCompany::find($insurance_company_id);
            if($insurance_company){
                $insurance_companies_name_arr[] = $insurance_company->company_name;
            }
        }
        $data['insurance_companies_name'] = implode(', ', $insurance_companies_name_arr);
        $insurance_types = $client->black_list_detail->purchased_insurance_types_id;
        $insurance_types_arr = explode(',', $insurance_types);
        $insurance_types_name_arr = [];
        foreach ($insurance_types_arr as $insurance_type_id) {
            $insurance_type = LineOfBusiness::find($insurance_type_id);
            if($insurance_type){
                $insurance_types_name_arr[] = $insurance_type->name;
            }
        }
        $data['insurance_types_name'] = implode(', ', $insurance_types_name_arr);

        $first_insurance_date = $client->black_list_detail->first_insurance_date;
        if($first_insurance_date){
            $insured_period_months = Carbon::parse($first_insurance_date)->diffInMonths(Carbon::now());
            if($insured_period_months <= 12){
                $insured_period = $insured_period_months . ' months';
            } else {
                $insured_period = floor($insured_period_months/12) . ' years';
            }
        } else {
            $insured_period = '-';
        }
        $data['insured_period'] = $insured_period;

        $insurance_types = $client->black_list_detail->blocked_insurance_types_id;
        $insurance_types_arr = explode(',', $insurance_types);
        $insurance_types_name_arr = [];
        foreach ($insurance_types_arr as $insurance_type_id) {
            $insurance_type = LineOfBusiness::find($insurance_type_id);
            if($insurance_type){
                $insurance_types_name_arr[] = $insurance_type->name;
            }
        }
        $data['insurance_type_cannot_purchase'] = implode(', ', $insurance_types_name_arr);
        $data['attachments'] = explode(',',$client->black_list_detail->attachments);
        $data['client_id'] = $client->id;
        $data['full_name'] = $client->full_name;
        $data['mobile_no'] = $client->mobile_no;
        $data['email_id'] = $client->email_id;
        $data['national_id_number'] = $client->national_id_number;
        $data['black_list_reason'] = $client->black_list_reason;
        $data['total_gross_premium_paid'] = $client->black_list_detail->total_gross_premium_paid;
        $data['total_net_premium_paid'] = $client->black_list_detail->total_net_premium_paid;

        $email = $request->admin_email;
        Mail::to($email)->queue(new SendBlackListClientInfo($data));
        return back()->with('success',__('messages.black_lists.email_sent'));
    }

}
