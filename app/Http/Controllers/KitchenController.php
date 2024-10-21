<?php

namespace App\Http\Controllers;

use App\Models\Kitchen;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    // List all kitchens
    public function index()
    {
        $kitchens = Kitchen::all();
        return response()->json($kitchens);
    }

    // Store a new kitchen
    public function store(Request $request)
    {
        $input = $request->validate([
            'kitchen_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'nullable|email',
            'profile_image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'location' => 'required|string',
            'categories' => 'required|array',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $kitchen = Kitchen::create($input);
        return response()->json([
            'message' => 'Kitchen created successfully',
            'kitchen' => $kitchen,
        ]);
    }

    // Update an existing kitchen
    public function update(Request $request, $id)
    {
        $kitchen = Kitchen::findOrFail($id);
        $input = $request->validate([
            'kitchen_name' => 'string',
            'phone_number' => 'string',
            'email' => 'nullable|email',
            'profile_image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'location' => 'string',
            'categories' => 'array',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $kitchen->update($input);
        return response()->json([
            'message' => 'Kitchen updated successfully',
            'kitchen' => $kitchen,
        ]);
    }

    // Delete a kitchen
    public function destroy($id)
    {
        $kitchen = Kitchen::findOrFail($id);
        $kitchen->delete();
        return response()->json([
            'message' => 'Kitchen deleted successfully',
        ]);
    }
}
