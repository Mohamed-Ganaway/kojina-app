<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Location;


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
        'order_type' => 'daily', // Assuming you have an order_type column in the orders table
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
        $request->validate([
            'main_course' => 'required|exists:meals,id',
            'pastries' => 'nullable|exists:meals,id',
            'salads' => 'nullable|exists:meals,id',
            'desserts' => 'nullable|exists:meals,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        $order = auth()->user()->orders()->create([
            'type' => 'event',
            'location_id' => $request->location_id,
            'status' => 'pending',
        ]);

        // Attach meals to the event order
        $order->meals()->attach($request->main_course);
        if ($request->pastries) $order->meals()->attach($request->pastries);
        if ($request->salads) $order->meals()->attach($request->salads);
        if ($request->desserts) $order->meals()->attach($request->desserts);

        return response()->json(['message' => 'Event order placed successfully']);
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
