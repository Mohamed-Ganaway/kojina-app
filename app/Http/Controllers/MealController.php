<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    // List all meals for a specific kitchen
    public function index($kitchenId)
    {
        $kitchen = Kitchen::findOrFail($kitchenId);
        $meals = $kitchen->meals;
        return response()->json($meals);
    }

    // Store a new meal
    public function store(Request $request, $kitchenId)
    {
        $kitchen = Kitchen::findOrFail($kitchenId);

        $input = $request->validate([
            'meal_name' => 'required|string',
            'meal_description' => 'required|string',
            'ingredients' => 'required|array',
            'main_ingredient' => 'required|string',
            'meal_image' => 'nullable|string',
            'price' => 'required|numeric',
            'meal_type' => 'required|string',
            'category' => 'required|string|in:' . implode(',', $kitchen->categories), // Validate category
            'discount' => 'nullable|numeric|min:0|max:100', // Percentage discount
        ]);

        $meal = $kitchen->meals()->create($input);

        return response()->json([
            'message' => 'Meal created successfully',
            'meal' => $meal,
        ]);
    }

    // Update an existing meal
    public function update(Request $request, $id)
    {
        $meal = Meal::findOrFail($id);

        $input = $request->validate([
            'meal_name' => 'string',
            'meal_description' => 'string',
            'ingredients' => 'array',
            'main_ingredient' => 'string',
            'meal_image' => 'nullable|string',
            'price' => 'numeric',
            'meal_type' => 'string',
            'category' => 'string',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $meal->update($input);

        return response()->json([
            'message' => 'Meal updated successfully',
            'meal' => $meal,
        ]);
    }

    // Delete a meal
    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();

        return response()->json([
            'message' => 'Meal deleted successfully',
        ]);
    }

    public function uploadMealImage(Request $request, $meal_id)
{
    $request->validate(['meal_image' => 'required|image|mimes:jpg,png,jpeg|max:2048']);
    $path = $request->file('meal_image')->store('images/meals', 'public');
    $url = Storage::url($path);

    Meal::where('id', $meal_id)->update(['meal_image' => $url]);
    return response()->json(['meal_image uploaded' => $url], 201);
}

}
