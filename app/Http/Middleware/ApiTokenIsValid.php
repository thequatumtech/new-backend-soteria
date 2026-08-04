<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->header('Authorization')==null || $request->header('Authorization')=='')
        {
            $arr=array('status' => false,'status_code' => 401, 'message' => 'unauthenticated');
            return response()->json($arr);
        }
        $access_token = $request->header('Authorization');
        $auth_header = explode(' ', $access_token);
        $token = $auth_header[1];
        $token_parts = explode('.', $token);
        if (empty($token_parts))
        {
            $arr=array('status' => false,'status_code' => 401, 'message' => 'unauthenticated');
            return response()->json($arr);
        }
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        if (empty($token_header_array['jti']))
        {
            $arr=array('status' => false,'status_code' => 401, 'message' => 'unauthenticated');
            return response()->json($arr);
        }
        $token_id = $token_header_array['jti'];

        $token_data = DB::table('oauth_access_tokens')->where('id', $token_id)->where('expires_at', '>=', Carbon::now())->first();
        if (is_null($token_data) || empty($token_data))
        {
            $arr=array('status' => false,'status_code' => 401, 'message' => 'unauthenticated');
            return response()->json($arr);
        }

        $request->token_id=$token_id;
        $request->user_id=$token_data->user_id;

        $user_data=Client::find($request->user_id);
        if (empty($user_data)) {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => 'User not found', 'data' => array()]);
        }
        $request->user_data=$user_data;

        return $next($request);
    }
}