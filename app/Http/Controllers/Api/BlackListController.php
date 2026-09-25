<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlackListDetail;
use Illuminate\Http\Request;

class BlackListController extends Controller
{
    public function checkBlackList(Request $request)
    {
        $clientId = $request->user_id;

        $blacklist = BlackListDetail::where('client_id', $clientId)
            ->whereNull('deleted_at')
            ->first();

        if (!$blacklist) {
            return response()->json([
                'status'  => true,
                'message' => __('messages.api.user_not_declined'),
            ]);
        }

        $insuranceTypeId = $request->insurance_type;

        if (!empty($blacklist->blocked_insurance_types_id)) {
            $blockedTypes = array_map('trim', explode(',', $blacklist->blocked_insurance_types_id));

            if (!in_array((string) $insuranceTypeId, $blockedTypes)) {
                return response()->json([
                    'status'  => true,
                    'message' => __('messages.api.user_not_declined_for_this_plan'),
                ]);
            }
        }

        return response()->json([
            'status'  => false,
            'message' => __('messages.api.user_declined_contact_support'),
        ], 403);
    }
}
