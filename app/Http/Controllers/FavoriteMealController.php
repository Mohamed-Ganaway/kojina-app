<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class FavoriteMealController extends Controller
{
    // Retrieve a user's favorite meals
    public function index(Request $request)
    {
        $favorites = $request->user()->favoriteMeals;
        return response()->json($favorites);
    }

    // Add a meal to favorites
    public function store(Request $request, $mealId)
    {
        $user = $request->user();
        if (!$user->favoriteMeals()->where('meal_id', $mealId)->exists()) {
            $user->favoriteMeals()->attach($mealId);
            return response()->json(['message' => 'Meal added to favorites']);
        }
        return response()->json(['message' => 'Meal is already in favorites'], 400);
    }

    // Remove a meal from favorites
    public function destroy(Request $request, $mealId)
    {
        $user = $request->user();
        if ($user->favoriteMeals()->where('meal_id', $mealId)->exists()) {
            $user->favoriteMeals()->detach($mealId);
            return response()->json(['message' => 'Meal removed from favorites']);
        }
        return response()->json(['message' => 'Meal is not in favorites'], 400);
    }
}
