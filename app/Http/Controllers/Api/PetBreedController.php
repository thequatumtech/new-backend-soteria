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
            'message' => 'Pet breeds fetched successfully.',
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
            'message' => 'Pet breeds not found.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Pet breeds fetched successfully.',
        'data' => $breeds,
    ]);
}
}