<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannersController extends Controller
{
    // public function getBanner(Request $request)
    // {
    //     try {
    //         $banners = Banner::where('is_active', true)
    //             ->latest()
    //             ->get()
    //             ->map(function ($banner) {
    //                 return [
    //                     'id'           => $banner->id,
    //                     'title'        => $banner->title,
    //                     'image'        => url($banner->image),
    //                     'redirect_url' => $banner->redirect_url,
    //                 ];
    //             });

    //         if ($banners->isEmpty()) {
    //             return response()->json([
    //                 'status'      => false,
    //                 'status_code' => 404,
    //                 'message'     => 'No active banners found',
    //                 'data'        => []
    //             ]);
    //         }

    //         return response()->json([
    //             'status'      => true,
    //             'status_code' => 200,
    //             'message'     => 'Banners retrieved successfully',
    //             'data'        => $banners
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'      => false,
    //             'status_code' => 500,
    //             'message'     => $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(),
    //             'data'        => []
    //         ]);
    //     }
    // }
    public function getBanner(Request $request)
{
    try {
        $banners = Banner::where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($banner) {

                $extension = strtolower(
                    pathinfo($banner->image, PATHINFO_EXTENSION)
                );

                $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];

                $type = in_array($extension, $videoExtensions)
                    ? 'video'
                    : 'image';

                return [
                    'id'           => $banner->id,
                    'title'        => $banner->title,
                    'image'        => url($banner->image),
                    'type'         => $type,
                    'redirect_url' => $banner->redirect_url,
                ];
            });

        if ($banners->isEmpty()) {
            return response()->json([
                'status'      => false,
                'status_code' => 404,
                'message'     => 'No active banners found',
                'data'        => []
            ]);
        }

        return response()->json([
            'status'      => true,
            'status_code' => 200,
            'message'     => 'Banners retrieved successfully',
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
}