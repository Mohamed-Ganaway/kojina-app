<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Location;
use App\Models\Meal;


class OrderController extends Controller
{
    // List all orders for a user
    public function index()
    {
        $orders = auth()->user()->orders;
        return response()->json($orders);
    }

    // Store a daily meal order
    public function storeDailyOrder(Request $request)
{
    // Validate the request
    $data = $request->validate([
        'location_id' => 'required|exists:locations,id',
        'meals' => 'required|array',
        'meals.*.meal_id' => 'required|exists:meals,id',
        'meals.*.quantity' => 'required|integer|min:1',
    ]);

    // Find the location
    $location = Location::find($data['location_id']);

    // Create the order
    $order = Order::create([
        'user_id' => auth()->id(),
        'location_id' => $location->id,
        'type' => $request->type,
        'status' => 'pending',
    ]);

    // Attach meals to the order
    foreach ($data['meals'] as $meal) {
        $order->meals()->attach($meal['meal_id'], ['quantity' => $meal['quantity']]);
    }

    return response()->json([
        'message' => 'Daily order created successfully',
        'order' => $order,
    ]);
}


    // Store an event order
    public function storeEventOrder(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'kitchen_id' => 'required|exists:kitchens,id',
        'location_id' => 'required|exists:locations,id',
        'taste_box' => 'boolean',
        'main_course' => 'array|required',
        'main_course.*.meal_id' => 'required|exists:meals,id',
        'main_course.*.quantity' => 'required|integer|min:1',
        'pastries' => 'array',
        'pastries.*.meal_id' => 'required|exists:meals,id',
        'pastries.*.quantity' => 'required|integer|min:1',
    ]);

    // Extract meal IDs for main course and pastries
    $mainCourseIds = collect($request->main_course)->pluck('meal_id');
    $pastriesIds = collect($request->pastries)->pluck('meal_id');

    // Fetch meals from the database
    $mainCourses = Meal::whereIn('id', $mainCourseIds)->get();
    $pastries = Meal::whereIn('id', $pastriesIds)->get();

    // Create the order
    $order = Order::create([
        'user_id' => auth()->id(),
        'kitchen_id' => $request->kitchen_id,
        'location_id' => $request->location_id,
        'type' => 'event',
        'status' => 'pending',
        'taste_box' => $request->taste_box ?? false,
    ]);

    // Attach meals to the order with their quantities
    foreach ($request->main_course as $meal) {
        $order->meals()->attach($meal['meal_id'], ['quantity' => $meal['quantity']]);
    }
    foreach ($request->pastries as $meal) {
        $order->meals()->attach($meal['meal_id'], ['quantity' => $meal['quantity']]);
    }

    return response()->json([
        'message' => 'Event order placed successfully!',
        'order' => $order
    ], 201);
}

    // Update order status (for kitchen admin or system)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined,pending',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully']);
    }

    // Delete an order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
