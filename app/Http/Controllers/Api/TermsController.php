<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermsController extends Controller
{
    public function getTerms(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $terms = DB::table('terms_and_conditions')
            ->whereNull('deleted_at')
            ->where('id', $request->input('id'))
            ->first();

        if (!$terms) {
            return response()->json([
                'success' => false,
                'message' => __('messages.api.terms_no_record_found')
            ], 404);
        }


        // Detect language from headers
        $lang = strtolower(
            $request->header('lang')
            ?? $request->header('Accept-Language')
            ?? 'en'
        );

        $lang = substr(trim(explode(',', $lang)[0]), 0, 2);

        // Select PDF based on language
        if ($lang === 'ar' && !empty($terms->arabic_file)) {
            $file = $terms->arabic_file;
        } else {
            $file = $terms->file;
        }

        // Fallback if the selected file is unavailable
        if (empty($file)) {
            $file = $terms->arabic_file ?? null;
        }

        // Convert filename to full URL
        $fileUrl = !empty($file)
            ? url('uploads/terms_and_conditions/' . $file)
            : null;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $terms->id,
                'message' => $terms->message,
                'file' => $fileUrl,
            ]
        ]);

    }
}
