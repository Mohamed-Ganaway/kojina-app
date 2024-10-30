<?php

namespace App\Http\Controllers;

use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
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

    public function show($id)
    {
        $kitchen = Kitchen::find($id);

        if (!$kitchen) {
            return response()->json(['message' => 'Kitchen not found'], 404);
        }

        return response()->json($kitchen);
    }

    public function uploadProfileImage(Request $request, $kitchen)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $kitchen = Kitchen::findOrFail($kitchen);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('kitchens/profile_images', 'public');
        $kitchen->profile_image = Storage::url($path);
        $kitchen->save();
    }

    return response()->json(['message' => 'Profile image uploaded successfully.', 'url' => $kitchen->profile_image]);
}




public function uploadCoverImage(Request $request, $kitchen_id)
{
    $request->validate(['cover_image' => 'required|image|mimes:jpg,png,jpeg|max:2048']);
    $path = $request->file('cover_image')->store('kitchens/cover_images', 'public');
    $url = Storage::url($path);

    Kitchen::where('id', $kitchen_id)->update(['cover_image' => $url]);
    return response()->json(['cover_image uploaded successfuly' => $url], 201);
}

}
