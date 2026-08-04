<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UtilsController extends Controller
{
    public function deleteMedia(Request $request, $id)  {
        if(auth()->guard('admin')->user()) {
            $media = Media::find($id);
            if($media) {
                $media->delete();
                return back()->with('success','media deleted');
            }
            return back()->with('error','media not found');
        }
        return back()->with('error','unathorized access denied');
    }

    public function downloadMedia(Request $request, $id) {
        $media = Media::find($id);
        if($media) {
           return response()->download($media->getPath(), $media->file_name);
        }
        return back()->with('error','media not found');
    }
}
