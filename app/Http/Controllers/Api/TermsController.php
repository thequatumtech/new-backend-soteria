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

        if (!empty($terms->file)) {
            $terms->file = url('uploads/terms_and_conditions/' . $terms->file);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'      => $terms->id,
                'message' => $terms->message,
                'file'    => $terms->file,
            ]
        ]);
    }
}
