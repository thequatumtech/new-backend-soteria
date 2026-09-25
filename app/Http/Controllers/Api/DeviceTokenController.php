<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'platform' => 'nullable|in:android,ios',
            'device_id' => 'nullable|string',
        ]);

        DeviceToken::updateOrCreate(
            ['fcm_token' => $request->fcm_token],
            [
                'user_id' => auth()->id(),
                'platform' => $request->platform,
                'device_id' => $request->device_id,
                'last_active_at' => now(),
            ]
        );

        return response()->json([
            'message' => __('messages.api.token_registered')
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        DeviceToken::where('fcm_token', $request->fcm_token)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'message' => __('messages.api.token_removed')
        ]);
    }
}
