<?php

namespace App\Http\Controllers\Api;

use App\Models\Cities;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\{ClientHomeInsurance,ClientLifeInsurance,ClientDentalsInsurance,ClientOfficeInsurance, PurchasePolicy,PolicyTransaction, VerifyOtp};
use App\Models\ClientDiscountCoupon;
use App\Models\ClientMessage;
use App\Models\Country;
use App\Models\District;
use App\Models\Nationality;
use App\Models\Occupations;
use App\Rules\AdultRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;
use Session;
use Illuminate\Support\Facades\Log;
class ClientController extends Controller
{   
    public function user_register(Request $request)
    {
        try {
               // new start

               if ($request->country_id) {
                $country = \App\Models\Country::find($request->country_id);
                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected country does not exist.',
                        'data' => []
                    ], 422);
                }
            }
    
            if ($request->country_id && $request->city_id) {
                $city = \App\Models\Cities::where('id', $request->city_id)
                    ->where('country_id', $request->country_id)
                    ->first();
    
                if (!$city) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected city does not belong to the selected country.',
                        'data' => []
                    ], 422);
                }
            }
    
            if ($request->city_id && $request->district_id) {
                $district = \App\Models\District::where('id', $request->district_id)
                    ->where('city_id', $request->city_id)
                    ->first();
    
                if (!$district) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected district does not belong to the selected city.',
                        'data' => []
                    ], 422);
                }
            }
            // end
            $user = Client::where("email_id", $request->email_id)->first();
            if (!empty($user)) {
                return response()->json(['status' => true, 'status_code' => 401, 'message' => 'Client Already Registered', 'data' => array()]);
            }
             // Helper function to generate unique file name
             $generateUniqueFileName = function($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };
    
            $client = new Client();
            if ($request->hasFile('id_front')) {
                $filename = $generateUniqueFileName($request->file('id_front'));
                $request->file('id_front')->move(public_path('front_user_id'), $filename);
                $client->id_front = 'public/front_user_id/' . $filename;
            }
            if ($request->hasFile('id_back')) {
                $filename = $generateUniqueFileName($request->file('id_back'));
                $request->file('id_back')->move(public_path('back_user_id'), $filename);
                $client->id_back = 'public/back_user_id/' . $filename;
            }
            if ($request->hasFile('profile_pic')) {
                $filename = $generateUniqueFileName($request->file('profile_pic'));
                $request->file('profile_pic')->move(public_path('profile_pic'), $filename);
                $client->profile_pic = 'public/profile_pic/' . $filename;
            }
            $client->fill($request->except(['id_front', 'id_back','profile_pic']));
            $client->password = Hash::make($request->password);
            $client->save();
             if (!empty($client->mobile_no)) {

                $client['otp'] = str_pad(rand(0000, 9999), 4, "0", STR_PAD_LEFT);

                $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                    'login_name'     => 'nitaq',
                    'login_password' => 'Netaq@2008',
                    'mobile_number'  => $client->mobile_no,
                    'msg'            => "Your OTP is: {$client['otp']}",
                    'from'           => 'iInsure',
                    'charset'        => 'UTF-8',
                    'otp_msg'        => 1,
                ]);
                Log::info('SMS RESPONSE', [
                    'phone' => $client->mobile_no,
                    'response' => $response->body()
                ]);

                if (
                    $response->successful() &&
                    (str_contains($response->body(), 'I01') || str_contains($response->body(), 'I02'))
                ) {
                    VerifyOtp::storeData($client);
                }
            }
            if (!empty($request->email_id)) {
                if (Auth::guard('client')->attempt(['email_id' => $request->email_id, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client=Client::find($data->id);
                    $client->fcm_token=$request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Register successfully', 'token' => $token, 'data' => $data]);
                }
            }
            else if(!empty($request->mobile_no))
            {
                if (Auth::guard('client')->attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client=Client::find($data->id);
                    $client->fcm_token=$request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Register successfully', 'token' => $token, 'data' => $data]);
                }
            }
           
            return response()->json(['status' => false, 'status_code' => 402, 'message' => 'Registration Not Successfully !', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
        }
    }
    public function updateProfile(Request $request)
    {
        // return json_encode($request->all());
        try {
            // new start

            if ($request->country_id) {
                $country = \App\Models\Country::find($request->country_id);
                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected country does not exist.',
                        'data' => []
                    ], 422);
                }
            }
    
            if ($request->country_id && $request->city_id) {
                $city = \App\Models\Cities::where('id', $request->city_id)
                    ->where('country_id', $request->country_id)
                    ->first();
    
                if (!$city) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected city does not belong to the selected country.',
                        'data' => []
                    ], 422);
                }
            }
    
            if ($request->city_id && $request->district_id) {
                $district = \App\Models\District::where('id', $request->district_id)
                    ->where('city_id', $request->city_id)
                    ->first();
    
                if (!$district) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Selected district does not belong to the selected city.',
                        'data' => []
                    ], 422);
                }
            }
            // end
            $user = Client::find($request->id);
            if (empty($user)) {
                return response()->json(['status' => false, 'status_code' => 401, 'message' => 'Client Not Found', 'data' => array()]);
            }
    
            // Helper function to generate unique file name
            $generateUniqueFileName = function($file) {
                return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            };
    
            if ($request->hasFile('id_front')) {
                $filename = $generateUniqueFileName($request->file('id_front'));
                $request->file('id_front')->move(public_path('front_user_id'), $filename);
                $user->id_front = 'public/front_user_id/' . $filename;
            }
            if ($request->hasFile('id_back')) {
                $filename = $generateUniqueFileName($request->file('id_back'));
                $request->file('id_back')->move(public_path('back_user_id'), $filename);
                $user->id_back = 'public/back_user_id/' . $filename;
            }
            if ($request->hasFile('profile_pic')) {
                $filename = $generateUniqueFileName($request->file('profile_pic'));
                $request->file('profile_pic')->move(public_path('profile_pic'), $filename);
                $user->profile_pic = 'public/profile_pic/' . $filename;
            }
    
            $user->fill($request->except(['id_front', 'id_back','profile_pic']));
            $user->save();
    
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Profile Updated Successfully!', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
        }
    }    
    public function login(Request $request)
    {
        try {
            if (!empty($request->email)) {
                if (Auth::guard('client')->attempt(['email_id' => $request->email, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client=Client::find($data->id);
                    $client->fcm_token=$request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged in successfully', 'token' => $token, 'data' => $data]);
                }
            }
            else if(!empty($request->mobile_no))
            {
                if (Auth::guard('client')->attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client=Client::find($data->id);
                    $client->fcm_token=$request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged in successfully', 'token' => $token, 'data' => $data]);
                }
            }
            return response()->json(['status' => false, 'status_code' => 402, 'message' => 'Email or password is invalid', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
        }
    }   
    public function getProfileDetail(Request $request)
    {
        try {
            $user_id=$request->user_id;
            $data=Client::find($user_id);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Profile successfully','data' => $data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => array()]);
        }
    }
    public function Logout(Request $request)
    {
        $token_id=$request->token_id;
        DB::table('oauth_access_tokens')->where('id', $token_id)->delete();
        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged out successfully', 'data' => array()]);
    }
    public function getPolicyDetails(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = PurchasePolicy::getAllPolicy($user_id);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get My Policy successfully', 'data' => $data]);
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => []]);
        }
    }
    public function storeTransaction(Request $request)
    {
        try {
            $policy=PurchasePolicy::where('id',$request->purchase_id)->where('payment_status',0)->first();
            if (!empty($policy)) 
            {
                $data = PolicyTransaction::storePolicyTransaction($request);
                PurchasePolicy::find($request->purchase_id)->update(['payment_status'=>1]);
                if ($data->client_coupon_id) {
                    $coupon=ClientDiscountCoupon::find($data->client_coupon_id);
                    $coupon->status = '0';
                    $coupon->save();
                }
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Payment successfully', 'data' => $data]);   
            }
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Policy Not Found', 'data' => array()]);   
        }
        catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage().' '.$e->getFile().' '.$e->getLine(), 'data' => []]);
        }
    }
    // public function sendForgotOtp(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'phone' => 'required'
    //         ]);
    //         $data=Client::where('mobile_no',$request->phone)->first();
    //         if (!empty($data)) {
    //             $data['otp']=str_pad(rand(0000,9999),4,"0",STR_PAD_LEFT);
    //             $apiKey = config('services.josms.api_key');
    //             $apiSecret = config('services.josms.api_secret');
    //             $senderId = config('services.josms.sender_id');
    //             $baseUrl = config('services.josms.base_url');
    //             Log::info('JO SMS Config', [
    //                 'api_key' => $apiKey,
    //                 'sender_id' => $senderId,
    //                 'base_url' => $baseUrl,
    //             ]);
    //             $response = Http::get($baseUrl, [
    //                 'senderid' => $senderId,
    //                 'numbers' => $data['mobile_no'],
    //                 'accname' => $apiKey,
    //                 'AccPass' => $apiSecret,
    //                 'msg' => "Your OTP is: {$data['otp']}",
    //             ]);
               
    //             $status=$response->successful();
    //             // $status=1;
    //             if($status==1)
    //             {
    //                 $data=VerifyOtp::storeData($data);
    //                 return response()->json(['status' => true, 'status_code' => 200, 'message' => 'OTP send successfully','data' => $data]);
    //             }   
    //             return response()->json(['status' => true, 'status_code' => 200, 'message' => 'OTP send failed','data' => array()]);
    //         }
    //         else{
    //             return response()->json(['status' => true, 'status_code' => 404, 'message' => 'User Details Not Found','data' => array()]);
    //         }
    //     }
    //     catch(\Exception $e)
    //     {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
    //     }
    // }
     public function sendForgotOtp(Request $request)
    {
        try {
            $request->validate(['phone' => 'required']);

            $data = Client::where('mobile_no', $request->phone)->first();
            if (!empty($data)) {
                $data['otp'] = str_pad(rand(0000, 9999), 4, "0", STR_PAD_LEFT);

                /* ==== ONLY CHANGE: SMSPRO ==== */
                $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                    'login_name'     => 'nitaq',
                    'login_password' => 'Netaq@2008',
                    'mobile_number'  => $data['mobile_no'],
                    'msg'            => "Your OTP is: {$data['otp']}",
                    'from'           => 'iInsure',
                    'charset'        => 'UTF-8',
                    'otp_msg'        => 1,
                ]);

                $status = ($response->successful() &&
                    (str_contains($response->body(), 'I01') || str_contains($response->body(), 'I02'))) ? 1 : 0;
                /* ============================= */

                if ($status == 1) {
                    $data = VerifyOtp::storeData($data);
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'OTP send successfully', 'data' => $data]);
                }

                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'OTP send failed', 'data' => []]);
            }

            return response()->json(['status' => true, 'status_code' => 404, 'message' => 'User Details Not Found', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
        }
    }

    // sendRegisterOtp
    public function sendRegisterOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required'
            ]);

            // FIX: Use object instead of array
         $data = new \stdClass();
            $data->id = null;        // VerifyOtp::storeData() uses $data->id
            $data->mobile_no = $request->phone;
            $data->otp = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
            $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                'login_name'     => 'nitaq',
                'login_password' => 'Netaq@2008',
                'mobile_number'  => $data->mobile_no,
                'msg'            => "Your OTP is: {$data->otp}",
                'from'           => 'iInsure',
                'charset'        => 'UTF-8',
                'otp_msg'        => 1,
            ]);

            $status = ($response->successful() &&
                (str_contains($response->body(), 'I01') || str_contains($response->body(), 'I02'))) ? 1 : 0;

            if ($status == 1) {
                $otpData = VerifyOtp::storeData($data);
                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'OTP sent successfully',
                    'data' => $otpData,
                ]);
            }

            return response()->json([
                'status' => false,
                'status_code' => 400,
                'message' => 'OTP sending failed',
                'data' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
    }
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'otp' => 'required',
                'password' => 'required'
            ]);
            $verificationOtp = VerifyOtp::where('id', $request->id)
            ->where('otp', $request->otp)
            ->where('date', '>=', now()) // Ensures OTP is not expired
            ->first();
            if (!empty($verificationOtp)) 
            {
                $client=Client::find($verificationOtp->client_id);
                $client->password=Hash::make($request->password);
                $client->save();
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Password Chanage Successfully','data' => array()]);
            }
            else{
                return response()->json(['status' => true, 'status_code' => 404, 'message' => 'Invalid Otp','data' => array()]);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ]);
            }

            $user = $request->user_data;

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Old password does not match.',
                    'data' => []
                ]);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'Password changed successfully!',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
