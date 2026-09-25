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
// use Illuminate\Support\Facades\App;
use Exception;
use Session;
use Illuminate\Support\Facades\Log;
use App\Models\UserSignature;
use PDF;
use App\Models\FinalPolicyPdf;

class ClientController extends Controller
{
    public function user_register(Request $request)
    {
        try {
            if ($request->country_id) {
                $country = Country::find($request->country_id);

                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.country_not_exist'),
                        'data' => []
                    ], 422);
                }
            }

            if ($request->country_id && $request->city_id) {
                $city = Cities::where('id', $request->city_id)
                    ->where('country_id', $request->country_id)
                    ->first();

                if (!$city) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.city_not_belong_country'),
                        'data' => []
                    ], 422);
                }
            }

            if ($request->city_id && $request->district_id) {
                $district = District::where('id', $request->district_id)
                    ->where('city_id', $request->city_id)
                    ->first();

                if (!$district) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.district_not_belong_city'),
                        'data' => []
                    ], 422);
                }
            }


            $user = Client::where("email_id", $request->email_id)->first();

            if (!empty($user)) {
                return response()->json([
                    'status' => true,
                    'status_code' => 401,
                    'message' => __('messages.api.client_already_registered'),
                    'data' => array()
                ]);
            }

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

            $client->fill($request->except([
                'id_front',
                'id_back',
                'profile_pic',
                'language'
            ]));

            $client->language = in_array(app()->getLocale(), ['en', 'ar'], true)
                ? app()->getLocale()
                : 'en';

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

            $registeredOtp = $client['otp'] ?? null;

            if (!empty($request->email_id)) {
                if (Auth::guard('client')->attempt([
                    'email_id' => $request->email_id,
                    'password' => $request->password
                ])) {

                    $data = Auth::guard('client')->user();

                    $token = $data->createToken(
                        rand(100000, 999999) . ' ' . now()
                    )->accessToken;

                    $client = Client::find($data->id);

                    $client->fcm_token = $request->fcm_token ?? '';

                    $client->language = app()->getLocale();

                    $client->save();

                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => __('messages.api.register_successfully'),
                        'token' => $token,
                        'data' => $data
                    ]);
                }
            } else if (!empty($request->mobile_no)) {

                if (Auth::guard('client')->attempt([
                    'mobile_no' => $request->mobile_no,
                    'password' => $request->password
                ])) {

                    $data = Auth::guard('client')->user();

                    $token = $data->createToken(
                        rand(100000, 999999) . ' ' . now()
                    )->accessToken;

                    $client = Client::find($data->id);

                    $client->fcm_token = $request->fcm_token ?? '';

                    $client->language = app()->getLocale();

                    $client->save();

                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => __('messages.api.register_successfully'),
                        'token' => $token,
                        'otp' => $registeredOtp,
                        'data' => $data
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'status_code' => 402,
                'message' => __('messages.api.registration_not_successfully'),
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

    public function updateProfile(Request $request)
    {
        try {

            if ($request->country_id) {
                $country = \App\Models\Country::find($request->country_id);

                if (!$country) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 422,
                        'message' => __('messages.api.country_not_exist'),
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
                        'message' => __('messages.api.city_not_belong_country'),
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
                        'message' => __('messages.api.district_not_belong_city'),
                        'data' => []
                    ], 422);
                }
            }


            $user = Client::find($request->id);

            if (empty($user)) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => __('messages.api.client_not_found'),
                    'data' => array()
                ]);
            }

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

            $user->fill($request->except([
                'id_front',
                'id_back',
                'profile_pic',
                'language'
            ]));

            $user->language = in_array(app()->getLocale(), ['en', 'ar'], true)
                ? app()->getLocale()
                : 'en';

            $user->save();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.profile_updated'),
                'data' => $user
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
                    return response()->json([
                        'status' => false,
                        'status_code' => 404,
                        'message' => __('messages.api.no_account'),
                        'data' => []
                    ]);
                }

                if (Auth::guard('client')->attempt([
                    'email_id' => $request->email,
                    'password' => $request->password
                ])) {

                    $data = Auth::guard('client')->user();

                    $token = $data->createToken(
                        rand(100000, 999999) . ' ' . now()
                    )->accessToken;

                    $client = Client::find($data->id);

                    $client->fcm_token = $request->fcm_token ?? '';

                    $client->language = in_array(app()->getLocale(), ['en', 'ar'], true)
                        ? app()->getLocale()
                        : 'en';

                    $client->save();

                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => __('messages.api.login_success'),
                        'token' => $token,
                        'data' => $data
                    ]);
                }
            } else if (!empty($request->mobile_no)) {

                $user = Client::where('mobile_no', $request->mobile_no)->first();

                if (empty($user)) {
                    return response()->json([
                        'status' => false,
                        'status_code' => 404,
                        'message' => __('messages.api.no_account'),
                        'data' => []
                    ]);
                }

                if (Auth::guard('client')->attempt([
                    'mobile_no' => $request->mobile_no,
                    'password' => $request->password
                ])) {

                    $data = Auth::guard('client')->user();

                    $token = $data->createToken(
                        rand(100000, 999999) . ' ' . now()
                    )->accessToken;

                    $client = Client::find($data->id);

                    $client->fcm_token = $request->fcm_token ?? '';

                    $client->language = in_array(app()->getLocale(), ['en', 'ar'], true)
                        ? app()->getLocale()
                        : 'en';

                    $client->save();

                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => __('messages.api.login_success'),
                        'token' => $token,
                        'data' => $data
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'status_code' => 402,
                'message' => __('messages.api.invalid_credentials'),
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

    public function getProfileDetail(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = Client::find($user_id);

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => __('messages.api.client_not_found'),
                    'data' => []
                ], 404);
            }

            $data->language = in_array(app()->getLocale(), ['en', 'ar'], true)
                ? app()->getLocale()
                : 'en';

            $data->save();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_profile_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => array()
            ]);
        }
    }

    public function Logout(Request $request)
    {
        $token_id = $request->token_id;

        DB::table('oauth_access_tokens')
            ->where('id', $token_id)
            ->delete();

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => __('messages.api.logout_success'),
            'data' => array()
        ]);
    }

    public function getPolicyDetails(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $data = PurchasePolicy::getAllPolicy($user_id);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_my_policy_successfully'),
                'data' => $data
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
        }
    }

    public function storeTransaction(Request $request)
    {
        try {
            $policy = PurchasePolicy::where('id', $request->purchase_id)
                ->where('payment_status', 0)
                ->first();

            if (!empty($policy)) {

                $data = PolicyTransaction::storePolicyTransaction($request);

                PurchasePolicy::find($request->purchase_id)
                    ->update(['payment_status' => 1]);

                if ($data->client_coupon_id) {
                    $coupon = ClientDiscountCoupon::find($data->client_coupon_id);
                    $coupon->status = '0';
                    $coupon->save();
                }

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => __('messages.api.payment_successfully'),
                    'data' => $data
                ]);
            }

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.policy_not_found'),
                'data' => array()
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data' => []
            ]);
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
    //
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

                $data['otp'] = str_pad(
                    rand(0000, 9999),
                    4,
                    "0",
                    STR_PAD_LEFT
                );

                $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                    'login_name' => 'nitaq',
                    'login_password' => 'Netaq@2008',
                    'mobile_number' => $data['mobile_no'],
                    'msg' => "Your OTP is: {$data['otp']}",
                    'from' => 'iInsure',
                    'charset' => 'UTF-8',
                    'otp_msg' => 1,
                ]);

                $status = (
                    $response->successful() &&
                    (
                        str_contains($response->body(), 'I01') ||
                        str_contains($response->body(), 'I02')
                    )
                ) ? 1 : 0;


                if ($status == 1) {

                    $data = VerifyOtp::storeData($data);

                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => __('messages.api.otp_send_successfully'),
                        'data' => $data
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => __('messages.api.otp_send_failed'),
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => true,
                'status_code' => 404,
                'message' => __('messages.api.user_details_not_found'),
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

    public function sendRegisterOtp(Request $request)
    {
        try {

            $request->validate([
                'phone' => 'required'
            ]);

            $data = new \stdClass();
            $data->id = null;
            $data->mobile_no = $request->phone;
            $data->otp = str_pad(
                rand(0, 9999),
                4,
                "0",
                STR_PAD_LEFT
            );

            $response = Http::get('https://sendsms.ngt.jo/http/send_sms_http.php', [
                'login_name' => 'nitaq',
                'login_password' => 'Netaq@2008',
                'mobile_number' => $data->mobile_no,
                'msg' => "Your OTP is: {$data->otp}",
                'from' => 'iInsure',
                'charset' => 'UTF-8',
                'otp_msg' => 1,
            ]);

            $status = (
                $response->successful() &&
                (
                    str_contains($response->body(), 'I01') ||
                    str_contains($response->body(), 'I02')
                )
            ) ? 1 : 0;

            if ($status == 1) {

                $otpData = VerifyOtp::storeData($data);

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => __('messages.api.otp_sent_successfully'),
                    'data' => $otpData,
                ]);
            }

            return response()->json([
                'status' => false,
                'status_code' => 400,
                'message' => __('messages.api.otp_sending_failed'),
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
                ->where('date', '>=', now())
                ->first();

            if (!empty($verificationOtp)) {

                $client = Client::find($verificationOtp->client_id);

                $client->password = Hash::make($request->password);
                $client->save();

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => __('messages.api.password_change_successfully'),
                    'data' => array()
                ]);
            } else {

                return response()->json([
                    'status' => true,
                    'status_code' => 404,
                    'message' => __('messages.api.invalid_otp'),
                    'data' => array()
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => $e->getMessage(),
                'data' => array()
            ]);
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
                    'message' => __('messages.api.old_password_not_match'),
                    'data' => []
                ]);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.password_changed_successfully'),
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
                'message' => __('messages.api.validation_failed'),
                'errors' => $validator->errors(),
            ], 422);
        }

        // $client = Client::find($request->client_id);

        $existingSignature = UserSignature::where('purchase_policy_id', $request->purchase_policy_id)
            ->where('client_id', $request->client_id)
            ->first();

        if ($existingSignature) {
            return response()->json([
                'status' => false,
                'message' => __('messages.api.policy_already_purchased'),
            ], 409);
        }

        $purchasePolicy = DB::table('purchase_policy')
            ->where('id', $request->purchase_policy_id)
            ->where('client_id', $request->client_id)
            ->first();

        if (!$purchasePolicy) {
            return response()->json([
                'status' => false,
                'message' => __('messages.api.policy_not_belong_client'),
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
            'message' => __('messages.api.signature_saved_successfully'),
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
        \Log::info('generate_final_pdf() CALLED', [
            'purchase_policy_id' => $request->purchase_policy_id ?? null,
            'request_data' => $request->all(),
        ]);

        try {

            $validator = Validator::make($request->all(), [
                'purchase_policy_id' => 'required|integer|exists:purchase_policy,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => __('messages.api.validation_failed'),
                    'errors' => $validator->errors(),
                ], 422);
            }


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
                    'message' => __('messages.api.policy_not_belong_client'),
                    'purchasePolicy' => $purchasePolicy,
                ], 404);
            }


            $client = $purchasePolicy->client;

            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => __('messages.api.client_not_found'),
                ], 404);
            }

            $company = InsuranceCompany::findOrFail($purchasePolicy->insurance_company_id);

            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => __('messages.api.insurance_company_not_found'),
                    'purchasePolicy' => $purchasePolicy,
                ], 404);
            }


            $combinedDetails = PurchasePolicy::getCombinedPolicyDetails($purchasePolicy);
            $plan = $this->getPolicyPlan($purchasePolicy);

            $userSig = UserSignature::where('purchase_policy_id', $purchasePolicy->id)->first();
            $userSignaturePath = ($userSig && !empty($userSig->signature))
                ? $userSig->signature
                : null;


            $data = (object) [
                // Company
                'insurance_company_id' => $purchasePolicy->insurance_company_id,
                'logo' => $combinedDetails->logo ?? $company->logo ?? null,
                'letterhead' => $combinedDetails->letterhead ?? $company->letterhead ?? null,
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
                'policy_plan_limit' => $combinedDetails->policy_plan_limit
                    ?? $purchasePolicy->policy_plan_limit
                    ?? 0,
                'net_premium' => $combinedDetails->net_premium
                    ?? $purchasePolicy->net_premium
                    ?? 0,
                'fees' => $combinedDetails->fees
                    ?? $purchasePolicy->fees
                    ?? 0,
                'stamps' => $combinedDetails->stamps
                    ?? $purchasePolicy->stamps
                    ?? 0,
                'sales_tax' => $combinedDetails->sales_tax
                    ?? $purchasePolicy->sales_tax
                    ?? 0,
                'cbj' => $combinedDetails->cbj
                    ?? $purchasePolicy->cbj
                    ?? 0,
                'sales_tax_cbj' => $combinedDetails->sales_tax_cbj
                    ?? $purchasePolicy->sales_tax_cbj
                    ?? 0,
                'gross_premium' => $combinedDetails->gross_premium
                    ?? $purchasePolicy->gross_premium
                    ?? 0,

                // Policy text
                'insurance_policy_text' => $combinedDetails->insurance_policy_text
                    ?? $company->insurance_policy_text
                    ?? '',

                // Full details object reference
                'details' => $combinedDetails,
            ];

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
                    'message' => __('messages.api.pdf_generation_not_supported'),
                ], 422);
            }

            $templateConfig = $policyTemplates[$policyType];


            if (!empty($combinedDetails)) {
                $purchasePolicy->net_premium = $purchasePolicy->net_premium
                    ?: ($combinedDetails->net_premium ?? 0);

                $purchasePolicy->fees = $purchasePolicy->fees
                    ?: ($combinedDetails->fees ?? 0);

                $purchasePolicy->stamps = $purchasePolicy->stamps
                    ?: ($combinedDetails->stamps ?? 0);

                $purchasePolicy->sales_tax = $purchasePolicy->sales_tax
                    ?: ($combinedDetails->sales_tax ?? 0);

                $purchasePolicy->cbj = $purchasePolicy->cbj
                    ?: ($combinedDetails->cbj ?? 0);

                $purchasePolicy->sales_tax_cbj = $purchasePolicy->sales_tax_cbj
                    ?: ($combinedDetails->sales_tax_cbj ?? 0);

                $purchasePolicy->gross_premium = $purchasePolicy->gross_premium
                    ?: ($combinedDetails->gross_premium ?? 0);

                $purchasePolicy->policy_plan_limit = $purchasePolicy->policy_plan_limit
                    ?: ($combinedDetails->limit
                        ?? $combinedDetails->policy_plan_limit
                        ?? 0);
            }

            if (!$plan) {
                $plan = new \stdClass();
            }

            if (is_object($plan)) {

                if (method_exists($plan, 'load')) {
                    try {
                        $plan->load('policy_covers');
                    } catch (\Throwable $th) {
                    }
                }

                $coversList = $plan->policy_covers
                    ?? $plan->covers
                    ?? collect([]);

                $plan->covers = $coversList;
                $plan->policy_covers = $coversList;

                $plan->limit = $plan->limit
                    ?? $purchasePolicy->policy_plan_limit
                    ?? ($combinedDetails->limit ?? 0);

                $plan->fees = $plan->fees
                    ?? $purchasePolicy->fees
                    ?? ($combinedDetails->fees ?? 0);

                $plan->stamps = $plan->stamps
                    ?? $purchasePolicy->stamps
                    ?? ($combinedDetails->stamps ?? 0);

                $plan->sales_tax = $plan->sales_tax
                    ?? $purchasePolicy->sales_tax
                    ?? ($combinedDetails->sales_tax ?? 0);

                $plan->cbj = $plan->cbj
                    ?? $purchasePolicy->cbj
                    ?? ($combinedDetails->cbj ?? 0);

                $plan->sales_tax_cbj = $plan->sales_tax_cbj
                    ?? $purchasePolicy->sales_tax_cbj
                    ?? ($combinedDetails->sales_tax_cbj ?? 0);

                $plan->net_premium_amount = $purchasePolicy->net_premium ?? 0;
                $plan->fees_amount = $purchasePolicy->fees ?? 0;
                $plan->stamps_amount = $purchasePolicy->stamps ?? 0;
                $plan->sales_tax_amount = $purchasePolicy->sales_tax ?? 0;
                $plan->cbj_amount = $purchasePolicy->cbj ?? 0;
                $plan->sales_tax_cbj_amount = $purchasePolicy->sales_tax_cbj ?? 0;
                $plan->gross_premium_amount = $purchasePolicy->gross_premium ?? 0;
            }


            $clientWithCurrency = \App\Models\Client::with('country.currency')
                ->find($purchasePolicy->client_id);

            $abbr = optional(
                optional(
                    optional($clientWithCurrency)->country
                )->currency
            )->abbreviation ?? 'JOD';

            $directory = public_path(
                'insurance_pdfs/' . $templateConfig['folder']
            );

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $filename = uniqid()
                . '_final_policy_'
                . $purchasePolicy->id
                . '.pdf';

            $path = $directory . '/' . $filename;

            $pdf = Pdf::loadView($templateConfig['view'], [
                'data' => $data,
                'purchase' => $purchasePolicy,
                'plan' => $plan,
                'abbr' => $abbr,
            ]);

            $pdf->save($path);

            $pdfUrl = url(
                'insurance_pdfs/'
                    . $templateConfig['folder']
                    . '/'
                    . $filename
            );


            // $purchasePolicy->policy_pdf_url = $path;
            // $purchasePolicy->save();

            FinalPolicyPdf::updateOrCreate(
                [
                    'policy_id' => $purchasePolicy->id,
                ],
                [
                    'final_pdf_url' => $path,
                    'client_id' => $purchasePolicy->client_id,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => __('messages.api.final_pdf_success'),
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
                'message' => __('messages.api.final_pdf_failed'),
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
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,ar',
        ]);

        $client = $request->user_data;

        if (!$client) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => __('messages.api.client_not_found'),
                'data' => []
            ], 404);
        }

        $client->language = $request->language;
        $client->save();

        \Illuminate\Support\Facades\App::setLocale($request->language);

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => __('messages.api.language_changed_successfully'),
            'data' => [
                'language' => $client->language,
            ]
        ]);
    }
}
