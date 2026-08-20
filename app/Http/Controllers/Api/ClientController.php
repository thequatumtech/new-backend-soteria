<?php

namespace App\Http\Controllers\Api;

use App\Models\Cities;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\{ClientHomeInsurance, ClientLifeInsurance, ClientDentalsInsurance, ClientOfficeInsurance, PurchasePolicy, PolicyTransaction, VerifyOtp};
use App\Models\ClientDiscountCoupon;
use App\Models\ClientMessage;
use App\Models\InsuranceCompany;
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
use App\Models\UserSignature;
use PDF;
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
            $generateUniqueFileName = function ($file) {
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
            $client->fill($request->except(['id_front', 'id_back', 'profile_pic']));
            $client->password = Hash::make($request->password);
            $client->save();
            if (!empty($client->mobile_no)) {

                $client['otp'] = str_pad(rand(0000, 9999), 4, "0", STR_PAD_LEFT);

                $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                    'login_name' => 'nitaq',
                    'login_password' => 'Netaq@2008',
                    'mobile_number' => $client->mobile_no,
                    'msg' => "Your OTP is: {$client['otp']}",
                    'from' => 'iInsure',
                    'charset' => 'UTF-8',
                    'otp_msg' => 1,
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
            // Keep the generated OTP so it can be returned in the response below (for app dev testing)
            $registeredOtp = $client['otp'] ?? null;
            if (!empty($request->email_id)) {
                if (Auth::guard('client')->attempt(['email_id' => $request->email_id, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client = Client::find($data->id);
                    $client->fcm_token = $request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Register successfully', 'token' => $token, 'data' => $data]);
                }
            } else if (!empty($request->mobile_no)) {
                if (Auth::guard('client')->attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client = Client::find($data->id);
                    $client->fcm_token = $request->fcm_token ?? '';
                    $client->save();
                    // return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Register successfully', 'token' => $token, 'data' => $data]);
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Register successfully', 'token' => $token, 'otp' => $registeredOtp, 'data' => $data]);
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
            $generateUniqueFileName = function ($file) {
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

            $user->fill($request->except(['id_front', 'id_back', 'profile_pic']));
            $user->save();

            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Profile Updated Successfully!', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
        }
    }
    // public function login(Request $request)
    // {
    //     try {
    //         if (!empty($request->email)) {
    //             if (Auth::guard('client')->attempt(['email_id' => $request->email, 'password' => $request->password])) {
    //                 $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
    //                 $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
    //                 $client=Client::find($data->id);
    //                 $client->fcm_token=$request->fcm_token ?? '';
    //                 $client->save();
    //                 return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged in successfully', 'token' => $token, 'data' => $data]);
    //             }
    //         }
    //         else if(!empty($request->mobile_no))
    //         {
    //             if (Auth::guard('client')->attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password])) {
    //                 $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
    //                 $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
    //                 $client=Client::find($data->id);
    //                 $client->fcm_token=$request->fcm_token ?? '';
    //                 $client->save();
    //                 return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged in successfully', 'token' => $token, 'data' => $data]);
    //             }
    //         }
    //         return response()->json(['status' => false, 'status_code' => 402, 'message' => 'Email or password is invalid', 'data' => []]);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => []]);
    //     }
    // }
    public function login(Request $request)
    {
        try {
            if (!empty($request->email)) {
                $user = Client::where('email_id', $request->email)->first();
                if (empty($user)) {
                    return response()->json(['status' => false, 'status_code' => 404, 'message' => 'No account found. Please Sign Up first to create an account.', 'data' => []]);
                }

                if (Auth::guard('client')->attempt(['email_id' => $request->email, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client = Client::find($data->id);
                    $client->fcm_token = $request->fcm_token ?? '';
                    $client->save();
                    return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged in successfully', 'token' => $token, 'data' => $data]);
                }
            } else if (!empty($request->mobile_no)) {
                $user = Client::where('mobile_no', $request->mobile_no)->first();
                if (empty($user)) {
                    return response()->json(['status' => false, 'status_code' => 404, 'message' => 'No account found. Please Sign Up first to create an account.', 'data' => []]);
                }

                if (Auth::guard('client')->attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password])) {
                    $data = Auth::guard('client')->user(); // Retrieve the authenticated user from the 'client' guard
                    $token = $data->createToken(rand(100000, 999999) . ' ' . now())->accessToken;
                    $client = Client::find($data->id);
                    $client->fcm_token = $request->fcm_token ?? '';
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
            $user_id = $request->user_id;
            $data = Client::find($user_id);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Profile successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
    public function Logout(Request $request)
    {
        $token_id = $request->token_id;
        DB::table('oauth_access_tokens')->where('id', $token_id)->delete();
        return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Logged out successfully', 'data' => array()]);
    }
    public function getPolicyDetails(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = PurchasePolicy::getAllPolicy($user_id);
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get My Policy successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
        }
    }
    public function storeTransaction(Request $request)
    {
        try {
            $policy = PurchasePolicy::where('id', $request->purchase_id)->where('payment_status', 0)->first();
            if (!empty($policy)) {
                $data = PolicyTransaction::storePolicyTransaction($request);
                PurchasePolicy::find($request->purchase_id)->update(['payment_status' => 1]);
                if ($data->client_coupon_id) {
                    $coupon = ClientDiscountCoupon::find($data->client_coupon_id);
                    $coupon->status = '0';
                    $coupon->save();
                }
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Payment successfully', 'data' => $data]);
            }
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Policy Not Found', 'data' => array()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => []]);
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
                    'login_name' => 'nitaq',
                    'login_password' => 'Netaq@2008',
                    'mobile_number' => $data['mobile_no'],
                    'msg' => "Your OTP is: {$data['otp']}",
                    'from' => 'iInsure',
                    'charset' => 'UTF-8',
                    'otp_msg' => 1,
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
                'login_name' => 'nitaq',
                'login_password' => 'Netaq@2008',
                'mobile_number' => $data->mobile_no,
                'msg' => "Your OTP is: {$data->otp}",
                'from' => 'iInsure',
                'charset' => 'UTF-8',
                'otp_msg' => 1,
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
            if (!empty($verificationOtp)) {
                $client = Client::find($verificationOtp->client_id);
                $client->password = Hash::make($request->password);
                $client->save();
                return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Password Chanage Successfully', 'data' => array()]);
            } else {
                return response()->json(['status' => true, 'status_code' => 404, 'message' => 'Invalid Otp', 'data' => array()]);
            }
        } catch (\Exception $e) {
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
    public function saveSignature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_policy_id' => 'required|integer|exists:purchase_policy,id',
            'client_id' => 'required|integer|exists:clients,id',
            'signature' => 'required|file|mimes:png|mimetypes:image/png|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingSignature = UserSignature::where('purchase_policy_id', $request->purchase_policy_id)
            ->where('client_id', $request->client_id)
            ->first();

        if ($existingSignature) {
            return response()->json([
                'status' => false,
                'message' => 'This policy is already purchased.',
            ], 409);
        }

        $purchasePolicy = DB::table('purchase_policy')
            ->where('id', $request->purchase_policy_id)
            ->where('client_id', $request->client_id)
            ->first();

        if (!$purchasePolicy) {
            return response()->json([
                'status' => false,
                'message' => 'Purchase policy does not belong to this client.',
            ], 404);
        }

        $file = $request->file('signature');

        $fileName = time() . '_' . uniqid() . '.' .
            $file->getClientOriginalExtension();

        $file->move(
            public_path('uploads/signatures'),
            $fileName
        );

        $signaturePath = 'uploads/signatures/' . $fileName;

        $signature = UserSignature::create([
            'purchase_policy_id' => $request->purchase_policy_id,
            'client_id' => $request->client_id,
            'signature' => $signaturePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Signature saved successfully.',
            'data' => [
                'id' => $signature->id,
                'purchase_policy_id' => $signature->purchase_policy_id,
                'client_id' => $signature->client_id,
                'signature' => asset($signature->signature),
            ],
        ], 200);
    }

    public function generate_final_pdf(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'purchase_policy_id' => 'required|integer|exists:purchase_policy,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Get Purchase Policy
            |--------------------------------------------------------------------------
            */

            $purchasePolicy = PurchasePolicy::with([
                'client',
                'insurance_company',

                // Plans
                'home_plan',
                'office_plan',
                'life_plan',
                'critical_illness_plan',
                'personal_accident_plan',
                'individual_medical_plan',
                'family_medical_plan',
                'pet_plan',
                'dental_plan',
                'travel_plan',
                'marine_plan',
                'motor_plan',

                // Policy-specific data
                'client_home_insurance',
                'client_office_insurance',
                'client_life_insurance',
                'client_critical_illness_insurance',
                'client_personal_accident_insurance',
                'client_family_medical_insurance',
                'client_pet_insurance',
                'client_dental_insurance',
                'client_travel_insurance',
                'client_marine_insurance',
                'client_motor_insurance',
            ])
                ->where('id', $request->purchase_policy_id)

                ->first();

            if (!$purchasePolicy) {
                return response()->json([
                    'status' => false,
                    'message' => 'Purchase policy does not belong to this client.',
                    'purchasePolicy' => $purchasePolicy,
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */

            $client = $purchasePolicy->client;

            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => 'Client not found.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Insurance Company
            |--------------------------------------------------------------------------
            */

            $company = InsuranceCompany::findOrFail($purchasePolicy->insurance_company_id);
            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insurance company not found.',
                    'purchasePolicy' => $purchasePolicy,
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Get Plan, Combined Policy Details & User Signature
            |--------------------------------------------------------------------------
            */

            $combinedDetails = PurchasePolicy::getCombinedPolicyDetails($purchasePolicy);
            $plan = $this->getPolicyPlan($purchasePolicy);

            $userSig = UserSignature::where('purchase_policy_id', $purchasePolicy->id)->first();
            $userSignaturePath = ($userSig && !empty($userSig->signature)) ? $userSig->signature : null;

            /*
            |--------------------------------------------------------------------------
            | Normalize data for PDF
            |--------------------------------------------------------------------------
            */

            $data = (object) [
                // Company
                'insurance_company_id' => $purchasePolicy->insurance_company_id,
                'logo' => $combinedDetails->logo ?? $company->logo ?? null,
                'company_name' => $combinedDetails->company_name ?? $company->company_name ?? '',
                'company_stamp' => $combinedDetails->company_stamp ?? $company->company_stamp ?? null,
                'authorized_signature' => $combinedDetails->authorized_signature ?? $company->authorized_signature ?? null,

                // User signature
                'user_signature' => $userSignaturePath,

                // Policy
                'police_no' => $purchasePolicy->policy_no,
                'plan_name' => $combinedDetails->plan_name
                    ?? $purchasePolicy->plan_name
                    ?? ($plan->plan_name ?? ''),
                'inception_date' => $purchasePolicy->inception_date,
                'effective_date' => $purchasePolicy->inception_date,
                'expiry_date' => $purchasePolicy->expiry_date,

                // Client
                'first_name' => $combinedDetails->first_name ?? $client->first_name ?? '',
                'last_name' => $combinedDetails->last_name ?? $client->father_name ?? '',
                'third_name' => $combinedDetails->third_name ?? $client->grandfather_name ?? '',
                'family_name' => $combinedDetails->family_name ?? $client->surname ?? '',

                // Premium
                'policy_plan_limit' => $combinedDetails->policy_plan_limit ?? $purchasePolicy->policy_plan_limit ?? 0,
                'net_premium' => $combinedDetails->net_premium ?? $purchasePolicy->net_premium ?? 0,
                'fees' => $combinedDetails->fees ?? $purchasePolicy->fees ?? 0,
                'stamps' => $combinedDetails->stamps ?? $purchasePolicy->stamps ?? 0,
                'sales_tax' => $combinedDetails->sales_tax ?? $purchasePolicy->sales_tax ?? 0,
                'cbj' => $combinedDetails->cbj ?? $purchasePolicy->cbj ?? 0,
                'sales_tax_cbj' => $combinedDetails->sales_tax_cbj ?? $purchasePolicy->sales_tax_cbj ?? 0,
                'gross_premium' => $combinedDetails->gross_premium ?? $purchasePolicy->gross_premium ?? 0,

                // Policy text
                'insurance_policy_text' => $combinedDetails->insurance_policy_text ?? $company->insurance_policy_text ?? '',

                // Full details object reference
                'details' => $combinedDetails,
            ];



            /*
            |--------------------------------------------------------------------------
            | Map policy_type to PDF template and storage folder
            |--------------------------------------------------------------------------
            */

            $policyTemplates = [
                1 => ['view' => 'pdf/home_policy', 'folder' => 'home_policy'],
                2 => ['view' => 'pdf/office_policy', 'folder' => 'office_policy'],
                3 => ['view' => 'pdf/life_policy', 'folder' => 'life_policy'],
                4 => ['view' => 'pdf/criticalIllness_policy', 'folder' => 'criticalIllness_policy'],
                5 => ['view' => 'pdf/personal_accident_policy', 'folder' => 'personal_accident_policy'],
                6 => ['view' => 'pdf/individual_medical_insurance', 'folder' => 'individual_medical_insurance'],
                7 => ['view' => 'pdf/family_medical_insurance', 'folder' => 'family_medical_insurance'],
                8 => ['view' => 'pdf/pet_policy', 'folder' => 'pet_policy'],
                9 => ['view' => 'pdf/dentals_policy', 'folder' => 'dentals_policy'],
                10 => ['view' => 'pdf/travel_policy', 'folder' => 'travel_policy'],
                11 => ['view' => 'pdf/marine_policy', 'folder' => 'marine_policy'],
                12 => ['view' => 'pdf/motor_policy', 'folder' => 'motor_policy'],
            ];

            $policyType = (int) $purchasePolicy->policy_type;

            if (!isset($policyTemplates[$policyType])) {
                return response()->json([
                    'status' => false,
                    'message' => 'PDF generation is not supported for this policy type.',
                ], 422);
            }

            $templateConfig = $policyTemplates[$policyType];

            /*
            |--------------------------------------------------------------------------
            | Load plan and normalize purchasePolicy/plan data for PDF template
            |--------------------------------------------------------------------------
            */

            if (!empty($combinedDetails)) {
                $purchasePolicy->net_premium       = $purchasePolicy->net_premium ?: ($combinedDetails->net_premium ?? 0);
                $purchasePolicy->fees              = $purchasePolicy->fees ?: ($combinedDetails->fees ?? 0);
                $purchasePolicy->stamps            = $purchasePolicy->stamps ?: ($combinedDetails->stamps ?? 0);
                $purchasePolicy->sales_tax         = $purchasePolicy->sales_tax ?: ($combinedDetails->sales_tax ?? 0);
                $purchasePolicy->cbj               = $purchasePolicy->cbj ?: ($combinedDetails->cbj ?? 0);
                $purchasePolicy->sales_tax_cbj     = $purchasePolicy->sales_tax_cbj ?: ($combinedDetails->sales_tax_cbj ?? 0);
                $purchasePolicy->gross_premium     = $purchasePolicy->gross_premium ?: ($combinedDetails->gross_premium ?? 0);
                $purchasePolicy->policy_plan_limit = $purchasePolicy->policy_plan_limit ?: ($combinedDetails->limit ?? $combinedDetails->policy_plan_limit ?? 0);
            }

            if (!$plan) {
                $plan = new \stdClass();
            }

            if (is_object($plan)) {
                if (method_exists($plan, 'load')) {
                    try {
                        $plan->load('policy_covers');
                    } catch (\Throwable $th) {
                        // ignore if relation isn't defined on a custom model
                    }
                }

                $coversList = $plan->policy_covers ?? $plan->covers ?? collect([]);
                $plan->covers = $coversList;
                $plan->policy_covers = $coversList;

                $plan->limit = $plan->limit ?? $purchasePolicy->policy_plan_limit ?? ($combinedDetails->limit ?? 0);
                $plan->fees = $plan->fees ?? $purchasePolicy->fees ?? ($combinedDetails->fees ?? 0);
                $plan->stamps = $plan->stamps ?? $purchasePolicy->stamps ?? ($combinedDetails->stamps ?? 0);
                $plan->sales_tax = $plan->sales_tax ?? $purchasePolicy->sales_tax ?? ($combinedDetails->sales_tax ?? 0);
                $plan->cbj = $plan->cbj ?? $purchasePolicy->cbj ?? ($combinedDetails->cbj ?? 0);
                $plan->sales_tax_cbj = $plan->sales_tax_cbj ?? $purchasePolicy->sales_tax_cbj ?? ($combinedDetails->sales_tax_cbj ?? 0);

                $plan->net_premium_amount = $purchasePolicy->net_premium ?? 0;
                $plan->fees_amount = $purchasePolicy->fees ?? 0;
                $plan->stamps_amount = $purchasePolicy->stamps ?? 0;
                $plan->sales_tax_amount = $purchasePolicy->sales_tax ?? 0;
                $plan->cbj_amount = $purchasePolicy->cbj ?? 0;
                $plan->sales_tax_cbj_amount = $purchasePolicy->sales_tax_cbj ?? 0;
                $plan->gross_premium_amount = $purchasePolicy->gross_premium ?? 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Currency abbreviation
            |--------------------------------------------------------------------------
            */

            $clientWithCurrency = \App\Models\Client::with('country.currency')->find($purchasePolicy->client_id);
            $abbr = optional(optional(optional($clientWithCurrency)->country)->currency)->abbreviation ?? 'JOD';

            /*
            |--------------------------------------------------------------------------
            | Generate PDF
            |--------------------------------------------------------------------------
            */

            $directory = public_path('insurance_pdfs/' . $templateConfig['folder']);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid() . '_final_policy_' . $purchasePolicy->id . '.pdf';
            $path = $directory . '/' . $filename;

                //    return response()->json([
                //     'status' => false,
                //     'message' => 'data',
                // 'data' => $data,
                // 'purchase' => $purchasePolicy,
                // 'plan' => $plan,
                // 'abbr' => $abbr,
                // ], 404);


            $pdf = Pdf::loadView($templateConfig['view'], [
                'data' => $data,
                'purchase' => $purchasePolicy,
                'plan' => $plan,
                'abbr' => $abbr,
            ]);

            $pdf->save($path);

            $pdfUrl = url('insurance_pdfs/' . $templateConfig['folder'] . '/' . $filename);

            /*
            |--------------------------------------------------------------------------
            | Persist the generated PDF URL on the purchase policy record
            |--------------------------------------------------------------------------
            */

            $purchasePolicy->policy_pdf_url = $path;
            $purchasePolicy->save();

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => true,
                'message' => 'Final policy PDF generated successfully.',
                'data' => [
                    'pdf_url' => $pdfUrl,
                ],
            ], 200);

        } catch (\Throwable $e) {

            \Log::error('Final Policy PDF Generation Failed', [
                'purchase_policy_id' => $request->purchase_policy_id ?? null,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Final policy PDF generation failed.',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    private function getPolicyPlan($purchasePolicy)
    {
        return match ((int) $purchasePolicy->policy_type) {
            1 => $purchasePolicy->home_plan,
            2 => $purchasePolicy->office_plan,
            3 => $purchasePolicy->life_plan,
            4 => $purchasePolicy->critical_illness_plan,
            5 => $purchasePolicy->personal_accident_plan,
            6 => $purchasePolicy->individual_medical_plan,
            7 => $purchasePolicy->family_medical_plan,
            8 => $purchasePolicy->pet_plan,
            9 => $purchasePolicy->dental_plan,
            10 => $purchasePolicy->travel_plan,
            11 => $purchasePolicy->marine_plan,
            12 => $purchasePolicy->motor_plan,

            default => null,
        };
    }

}
