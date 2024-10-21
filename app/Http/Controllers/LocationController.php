<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    // List all user locations
    public function index()
    {
        $locations = auth()->user()->locations;
        return response()->json($locations);
    }

    // Store a new location
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
        ]);

        $location = auth()->user()->locations()->create($request->all());

        return response()->json(['message' => 'Location added successfully', 'location' => $location]);
    }

    // Update a location
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
        ]);

        $location->update($request->all());

        return response()->json(['message' => 'Location updated successfully']);
    }

    // Delete a location
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['message' => 'Location deleted successfully']);
    }
}
