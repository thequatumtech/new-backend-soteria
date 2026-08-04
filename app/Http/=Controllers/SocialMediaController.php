<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        $social_media_data = SocialMedia::all();
        return view('content.socialmedia.list', compact('social_media_data'));

    }

    public function social_media_create(Request $request)
    {
        $social_media = $request->social_media;
        $model = new SocialMedia();
        $model->truncate();
        $data = [];
        if(!empty($social_media)) {
            foreach ($social_media as $single) {
                if (empty($single['platform']) || empty($single['url'])) {
                    continue;
                }
                $data[] = [
                    'platform' => $single['platform'],
                    'url' => $single['url'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            $model->insert($data);
            return redirect()->route('social_media.list')->with(['success'=>__('messages.success_message.social_platform_update')]);
        } else {
            return redirect()->route('social_media.list')->with(['error'=>__('messages.error_message.social_platform_nothing_to_update')]);
        }
    }
}
