<?php

namespace App\Http\Controllers;

use App\Mail\SendCoupon;
use App\Models\Client;
use App\Models\ClientDiscountCoupon;
use App\Models\DiscountCoupon;
use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DiscountCouponController extends Controller
{

    public function index(Request $request)
    {
        $coupons = DiscountCoupon::all();
        return view('admin.coupons.index', compact('coupons'));

    }

    public function add_coupon(Request $request)
    {
        $insurance_companies = InsuranceCompany::all();
//        $line_of_businesses = LineOfBusiness::all();
        $clients = Client::all();
        $now = now()->format('ymd');
        do {
            $randomNumber = rand(0, 9999);
            $formattedNumber = str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $coupon_code = $now.$formattedNumber;
            $exists = DiscountCoupon::where('coupon_code', $coupon_code)->exists();
        } while ($exists);
        return view('admin.coupons.add_coupon',compact('insurance_companies',/*'line_of_businesses',*/'coupon_code','clients'));
    }

    public function edit_coupon(Request $request)
    {
        $coupon = DiscountCoupon::find($request->id);
        $clients = Client::all();
        $insurance_companies = InsuranceCompany::all();
//        $line_of_businesses = LineOfBusiness::all();
        $company_line_of_businesses = InsuranceCompany::find($coupon->insurance_company_id)->line_of_business_id;
        $line_of_businesses = LineOfBusiness::whereIn('id',json_decode($company_line_of_businesses))->get();
        $coupon_code = $coupon->coupon_code;
        return view('admin.coupons.add_coupon', compact('coupon','insurance_companies','line_of_businesses','coupon_code','clients'));
    }

    public function save_coupon(Request $request)
    {
        if($request->form_type == 'add'){
            $coupon = new DiscountCoupon();
            $coupon->coupon_code = $request->coupon_code;
            $coupon->insurance_company_id = $request->insurance_company_id;
            $coupon->line_of_business_id = $request->line_of_business_id;
            $coupon->percentage = $request->percentage;
            $coupon->effective_date = Carbon::parse($request->effective_date)->format('Y-m-d');
            $coupon->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
            $coupon->client_ids = isset($request->selected_clients)?$request->client_ids:null;
            $coupon->description = isset($request->description)?$request->description:null;
            $coupon->send_to = $request->send_coupon_to;
            $coupon->save();

            $file = 'attachment';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/discount_coupons/' . $coupon->id), $newFilename);

                $coupon->$file = $newFilename; // Store the filename in the database
                $coupon->save();
            }

            if($request->send_coupon_to){
                if($request->selected_clients) {
                    $client_ids = explode(',', $request->selected_clients);
                    foreach ($client_ids as $client_id) {
                        $client_coupon = new ClientDiscountCoupon();
                        $client_coupon->coupon_code = $coupon->coupon_code;
                        $client_coupon->coupon_id = $coupon->id;
                        $client_coupon->client_id = $client_id;
                        $client_coupon->insurance_company_id = $coupon->insurance_company_id;
                        $client_coupon->line_of_business_id = $coupon->line_of_business_id;
                        $client_coupon->percentage = $coupon->percentage;
                        $client_coupon->effective_date = Carbon::parse($coupon->effective_date)->format('Y-m-d');
                        $client_coupon->expiry_date = Carbon::parse($coupon->expiry_date)->format('Y-m-d');
                        $client_coupon->description =$coupon->description;
                        $client_coupon->send_to = $coupon->send_to;
                        $client_coupon->attachment = $coupon->attachment; // Store the filename in the database
                        $client_coupon->status = $coupon->status??1;
                        $client_coupon->save();
                    }
                }
                $message = __('messages.discount_coupons.send_success');
            } else{
                $message = __('messages.discount_coupons.add_success');
            }
        } else if($request->form_type == 'edit'){
            $coupon_id = $request->coupon_id;
            $coupon = DiscountCoupon::find($coupon_id);
            $coupon->insurance_company_id = $request->insurance_company_id;
            $coupon->line_of_business_id = $request->line_of_business_id;
            $coupon->percentage = $request->percentage;
            $coupon->effective_date = Carbon::parse($request->effective_date)->format('Y-m-d');
            $coupon->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
            $coupon->client_ids = isset($request->selected_clients)?$request->client_ids:null;
            $coupon->description = isset($request->description)?$request->description:null;
            $coupon->send_to = $request->send_coupon_to;
            $coupon->save();

            $file = 'attachment';
            if ($request->hasFile($file)) {
                $uploadedFile = $request->file($file);
                $filename = $uploadedFile->getClientOriginalName(); // Original filename
                $newFilename = $file."_file_" . time() . "." .$uploadedFile->getClientOriginalExtension(); // New filename with timestamp
                $uploadedFile->move(public_path('uploads/discount_coupons/' . $coupon->id), $newFilename);

                $coupon->$file = $newFilename; // Store the filename in the database
                $coupon->save();
            }
            if($request->send_coupon_to){
                if($request->selected_clients) {
                    $client_ids = explode(',', $request->selected_clients);
                    foreach ($client_ids as $client_id) {
                        $client_coupon = new ClientDiscountCoupon();
                        $client_coupon->coupon_code = $coupon->coupon_code;
                        $client_coupon->coupon_id = $coupon->id;
                        $client_coupon->client_id = $client_id;
                        $client_coupon->insurance_company_id = $coupon->insurance_company_id;
                        $client_coupon->line_of_business_id = $coupon->line_of_business_id;
                        $client_coupon->percentage = $coupon->percentage;
                        $client_coupon->effective_date = Carbon::parse($coupon->effective_date)->format('Y-m-d');
                        $client_coupon->expiry_date = Carbon::parse($coupon->expiry_date)->format('Y-m-d');
                        $client_coupon->description =$coupon->description;
                        $client_coupon->send_to = $coupon->send_to;
                        $client_coupon->attachment = $coupon->attachment; // Store the filename in the database
                        $client_coupon->status = $coupon->status??1;
                        $client_coupon->save();
                    }
                }
                $message = __('messages.discount_coupons.send_success');
            } else {
                $message = __('messages.discount_coupons.edit_success');
            }
        }
        return redirect()->route('coupons')->with('success', $message);
    }

    public function view_coupon(Request $request)
    {
        $coupon = DiscountCoupon::find($request->id);
        $clients = Client::all();
        $insurance_company = InsuranceCompany::find($coupon->insurance_company_id);
        $line_of_business = LineOfBusiness::find($coupon->line_of_business_id);
        return view('admin.coupons.view_coupon', compact('coupon','clients','insurance_company','line_of_business'));
    }

    public function send_coupon(Request $request)
    {
        $coupon = DiscountCoupon::find($request->coupon_id);
        if ($request->selected_clients) {
            $client_ids = explode(',', $request->selected_clients);
            foreach ($client_ids as $client_id) {
                $client_coupon = new ClientDiscountCoupon();
                $client_coupon->coupon_id = $coupon->id;
                $client_coupon->coupon_code = $coupon->coupon_code;
                $client_coupon->client_id = $client_id;
                $client_coupon->insurance_company_id = $coupon->insurance_company_id;
                $client_coupon->line_of_business_id = $coupon->line_of_business_id;
                $client_coupon->percentage = $coupon->percentage;
                $client_coupon->effective_date = Carbon::parse($coupon->effective_date)->format('Y-m-d');
                $client_coupon->expiry_date = Carbon::parse($coupon->expiry_date)->format('Y-m-d');
                $client_coupon->description = $coupon->description;
                $client_coupon->send_to = $request->send_coupon_to;
                $client_coupon->attachment = $coupon->attachment; // Store the filename in the database
                $client_coupon->status = $coupon->status;
                $client_coupon->save();
            }
        }
        $pending_clients = ClientDiscountCoupon::where('send_to', '1')->where('is_sent', '0')->where('status', '1')->get();
        foreach ($pending_clients as $client) {
            $found = Client::where('id', $client->client_id)->first();
            if ($found) {
                Mail::to($found->email_id)->queue(new SendCoupon($client));
            }
            $client->is_sent = '1';
            $client->save();
        }
        $message = __('messages.discount_coupons.send_success');
        return back()->with('success', $message);
    }

    public function delete_coupon(Request $request)
    {
        $found = DiscountCoupon::find($request->coupon_id);
        $found->delete();
        return redirect()->back()->with('success',__('messages.discount_coupons.delete_success'));
    }

    public function change_status(Request $request)
    {
        $found = DiscountCoupon::find($request->id);
        $found->status = $request->status;
        $found->save();
        return response()->json(['success'=>'Status changed successfully.']);
    }

}
