<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kitchen;
use Illuminate\Http\Request;

class FavoriteKitchenController extends Controller
{
    // Retrieve a user's favorite kitchens
    public function index(Request $request)
    {
        $favorites = $request->user()->favoriteKitchens;
        return response()->json($favorites);
    }

    // Add a kitchen to favorites
    public function store(Request $request, $kitchenId)
    {
        $user = $request->user();
        if (!$user->favoriteKitchens()->where('kitchen_id', $kitchenId)->exists()) {
            $user->favoriteKitchens()->attach($kitchenId);
            return response()->json(['message' => 'Kitchen added to favorites']);
        }
        return response()->json(['message' => 'Kitchen is already in favorites'], 400);
    }

    // Remove a kitchen from favorites
    public function destroy(Request $request, $kitchenId)
    {
        $user = $request->user();
        if ($user->favoriteKitchens()->where('kitchen_id', $kitchenId)->exists()) {
            $user->favoriteKitchens()->detach($kitchenId);
            return response()->json(['message' => 'Kitchen removed from favorites']);
        }
        return response()->json(['message' => 'Kitchen is not in favorites'], 400);
    }
}
