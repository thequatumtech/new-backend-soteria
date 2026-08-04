<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermsController extends Controller
{
    public function getTerms()
    {
        $terms = DB::table('terms_and_conditions')
                    ->orderBy('created_at', 'desc')
                    ->first();

        if (!$terms) {
            return response()->json([
                'success' => false,
                'message' => 'No terms and conditions found'
            ], 404);
        }

        if (!empty($terms->file)) {
            $terms->file = url('uploads/terms_and_conditions/' . $terms->file);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => $terms->message,
                'file' => $terms->file
            ]
        ]);
    }
}