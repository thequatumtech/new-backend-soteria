<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PetBreed;
use Illuminate\Http\Request;

class PetBreedController extends Controller
{
    /**
     * Get all pet breeds.
     */
    public function index(Request $request)
    {
        $query = PetBreed::query();

        // Optional filter: ?type=dog or ?type=cat
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $breeds = $query->orderBy('type')
            ->orderBy('breed')
            ->get();

        return response()->json([
            'success' => true,
            'message' => __('messages.api.pet_breeds_fetched_successfully'),
            'data' => $breeds,
        ]);
    }

    /**
     * Get a single pet breed.
     */
    public function show($type)
    {
        $breeds = PetBreed::where('type', $type)->get();

        if ($breeds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.api.pet_breeds_not_found'),
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.api.pet_breeds_fetched_successfully'),
            'data' => $breeds,
        ]);
    }
}
