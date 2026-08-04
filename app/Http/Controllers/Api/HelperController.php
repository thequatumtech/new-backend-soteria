<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public static function sendJsonResponse($status, $data = null, $message = null) {
        return response()->json([
          'status' => $status,
            'data' => $data,
          'message' => $message
        ]);
    }
}
