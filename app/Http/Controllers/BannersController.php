<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannersController extends Controller
{
    public function getBannerList(Request $request)
    {
        try {
            $banners = Banner::where('is_active', true)->get()->map(function ($banner) {
                return [
                    'title'        => $banner->title,
                    'image'        => url($banner->image),
                    'redirect_url' => $banner->redirect_url,
                     'runtime' => $banner->runtime,

                ];
            });

            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'message'     => 'Get Banner List successfully',
                'data'        => $banners
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'message'     => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
                'data'        => []
            ]);
        }
    }

    public function bannerindex()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    // public function bannercreate(Request $request)
    // {
    //     $request->validate([
    //         'name'  => 'required|string|max:150',
    //         'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //         'link'  => 'nullable|url|max:255',
    //         'video' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:51200',

    //     ]);

    //     $uploadPath = public_path('uploads/banner');
    //     if (!file_exists($uploadPath)) {
    //         mkdir($uploadPath, 0755, true);
    //     }

    //     $image = time() . '.' . $request->image->extension();
    //     $request->image->move($uploadPath, $image);

    //     $videoPath = null;

    //     if ($request->hasFile('video')) {
    //         $video = time() . '_video.' . $request->video->extension();
    //         $request->video->move($uploadPath, $video);

    //         $videoPath = 'uploads/banner/' . $video;
    //     }

    //     Banner::create([
    //         'title'        => $request->name,
    //         'image'        => 'uploads/banner/' . $image,
    //         'video'        => $videoPath,
    //         'redirect_url' => $request->link,
    //         'is_active'    => true,
    //     ]);

    //     return redirect()
    //         ->route('banner.list')
    //         ->with('success', 'Banner Created Successfully');
    // }
    public function bannercreate(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:150',
            'image' => 'required|file|mimes:jpeg,png,jpg,mp4,webm,ogg,mov|max:51200',
            'link'  => 'nullable|url|max:255',
            'runtime' => 'required|integer|in:5,10,15,20,25,30,35,40,45,50,55,60',

        ]);

        $uploadPath = public_path('uploads/banner');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = time() . '.' . $request->image->extension();

        $request->image->move($uploadPath, $file);

        Banner::create([
            'title'        => $request->name,
            'image'        => 'uploads/banner/' . $file,
            'redirect_url' => $request->link,
            'is_active'    => true,
         'runtime' => $request->runtime,

        ]);

        return redirect()
            ->route('banner.list')
            ->with('success', 'Banner Created Successfully');
    }

    // public function bannerupdate(Request $request)
    // {
    //     $request->validate([
    //         'cd_id'  => 'required|exists:banners,id',
    //         'name'   => 'required|string|max:150',
    //         'image'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //         'video' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:51200',
    //         'link'   => 'nullable|url|max:255',
    //         'status' => 'required|in:0,1',
    //     ]);

    //     $banner = Banner::findOrFail($request->cd_id);

    //     if ($request->hasFile('image')) {
    //         if ($banner->image && file_exists(public_path($banner->image))) {
    //             unlink(public_path($banner->image));
    //         }

    //         $uploadPath = public_path('uploads/banner');
    //         if (!file_exists($uploadPath)) {
    //             mkdir($uploadPath, 0755, true);
    //         }

    //         $image = time() . '.' . $request->image->extension();
    //         $request->image->move($uploadPath, $image);
    //         $banner->image = 'uploads/banner/' . $image;
    //     }

    //     // Update video if a new video is uploaded
    //     if ($request->hasFile('video')) {
    //         // Delete old video
    //         if ($banner->video && file_exists(public_path($banner->video))) {
    //             unlink(public_path($banner->video));
    //         }

    //         $video = time() . '_video.' . $request->video->extension();
    //         $request->video->move($uploadPath, $video);

    //         $banner->video = 'uploads/banner/' . $video;
    //     }
    //     $banner->title        = $request->name;
    //     $banner->redirect_url = $request->link;
    //     $banner->is_active    = $request->status;
    //     $banner->save();

    //     return redirect()
    //         ->route('banner.list')
    //         ->with('success', 'Banner Updated Successfully');
    // }
    public function bannerupdate(Request $request)
    {
        $request->validate([
            'cd_id' => 'required|exists:banners,id',
            'name'  => 'required|string|max:150',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,mp4,webm,ogg,mov|max:51200',
            'link'  => 'nullable|url|max:255',
            'status' => 'required|in:0,1',
             'runtime' => 'required|integer|in:5,10,15,20,25,30,35,40,45,50,55,60',

        ]);

        $banner = Banner::findOrFail($request->cd_id);

        if ($request->hasFile('image')) {
            // Delete old image/video file
            if ($banner->image && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            $uploadPath = public_path('uploads/banner');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file = time() . '.' . $request->image->extension();

            $request->image->move($uploadPath, $file);

            // Store either image or video path in the image column
            $banner->image = 'uploads/banner/' . $file;
        }

        $banner->title        = $request->name;
        $banner->redirect_url = $request->link;
        $banner->is_active    = $request->status;
                $banner->runtime = $request->runtime;


        $banner->save();

        return redirect()
            ->route('banner.list')
            ->with('success', 'Banner Updated Successfully');
    }

    public function bannerdestroy(Request $request)
    {
        $request->validate([
            'banner_id' => 'required|exists:banners,id',
        ]);

        $banner = Banner::findOrFail($request->banner_id);

        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }
        if ($banner->video && file_exists(public_path($banner->video))) {
            unlink(public_path($banner->video));
        }


        $banner->delete();

        return redirect()
            ->route('banner.list')
            ->with('success', 'Banner Deleted Successfully');
    }
}
