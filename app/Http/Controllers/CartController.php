<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Meal;

class CartController extends Controller
{
    // List all items in the cart
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('meal')->get();
        return response()->json($cartItems);
    }

    // Add an item to the cart
    public function add(Request $request)
{
    $user = auth()->user(); // Get the authenticated user

    $meal = Meal::find($request->meal_id);

    if (!$meal) {
        return response()->json(['error' => 'Meal not found'], 404);
    }

    // Add the meal to the user's cart
    $cartItem = $user->cartItems()->updateOrCreate(
        ['meal_id' => $meal->id], 
        ['quantity' => $request->quantity]
    );

    return response()->json([
        'message' => 'Meal added to cart',
        'cartItem' => $cartItem
    ]);
}


    // Update an item in the cart
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart item updated successfully']);
    }

    // Remove an item from the cart
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    // Clear the cart
    public function clear()
    {
        auth()->user()->cartItems()->delete();
        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
