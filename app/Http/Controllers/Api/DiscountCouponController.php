<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Mail\SendCoupon;
use App\Models\Client;
use App\Models\ClientDiscountCoupon;
use App\Models\DiscountCoupon;
use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use App\Models\PurchasePolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Type\Decimal;

class DiscountCouponController extends Controller
{
    public function getDiscountCoupons(Request $request)
    {
        try {
            $data = ClientDiscountCoupon::with('line_of_business')
                ->where('client_id', $request->user_id)
                ->where('status', '1')
                ->get()
                ->unique('coupon_id')
                ->values();

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => __('messages.api.get_discount_coupons_successfully'),
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

    public function getDiscountAmount(Request $request)
    {
        try {
            $coupon_data = ClientDiscountCoupon::with('line_of_business')->where('coupon_code', $request->coupon_code)->where('client_id', $request->user_id)->where('expiry_date', '>=', date('Y-m-d'))->first();
            if ($coupon_data) {
                $policy_data = PurchasePolicy::find($request->purchase_id);
                if ($policy_data) {
                    if ($policy_data->policy_type == $coupon_data->line_of_business->new_id) {
                        $data = array();
                        $net_premium_per_amount = ($policy_data->net_premium * $coupon_data->percentage) / 100;
                        $net_premium = $policy_data->net_premium - $net_premium_per_amount;
                        $data['coupon_id'] = $coupon_data->id;
                        $data['coupon_code'] = $coupon_data->coupon_code;
                        $data['net_premium'] = (float)number_format($net_premium, 2);
                        $data['fees'] = (float)$policy_data->fees;
                        $data['stamps'] = (float)$policy_data->stamps;
                        $data['sales_tax'] = (float)$policy_data->sales_tax;
                        $data['total_net_premium'] = (float)($net_premium + $policy_data->fees + $policy_data->stamps + $policy_data->sales_tax);
                        return response()->json(['status' => true, 'status_code' => 200, 'message' => __('messages.api.get_coupon_discount_successfully'), 'data' => $data]);
                    } else {
                        return response()->json(['status' => true, 'status_code' => 401, 'message' => __('messages.api.invalid_coupon_code_for_insurance'), 'data' => array()]);
                    }
                } else {
                    return response()->json(['status' => true, 'status_code' => 402, 'message' => __('messages.api.purchase_policy_record_not_found'), 'data' => array()]);
                }
            } else {
                return response()->json(['status' => true, 'status_code' => 403, 'message' => __('messages.api.invalid_coupon_discount_code'), 'data' => array()]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(), 'data' => array()]);
        }
    }
}
