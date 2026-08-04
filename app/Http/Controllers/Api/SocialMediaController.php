<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function getSocialMedia(Request $request)
    {
        try
        {
            $social_media_data = SocialMedia::all();
            return response()->json(['status' => true, 'status_code' => 200, 'message' => 'Get Social Media successfully','data' => $social_media_data]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage(), 'data' => array()]);
        }
    }
}
