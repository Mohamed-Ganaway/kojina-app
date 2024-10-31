<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\FavoriteKitchenController;
use App\Http\Controllers\FavoriteMealController;




Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function(){


    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    
 
    
    Route::prefix('kitchens')->group(function () {
        Route::get('/', [KitchenController::class, 'index']); 
        Route::post('/', [KitchenController::class, 'store']); 
        Route::post('/{id}/uploadProfileImage', [KitchenController::class, 'uploadProfileImage']);
        Route::post('/{id}/uploadCoverImage', [KitchenController::class, 'uploadCoverImage']);
        Route::get('/{id}', [KitchenController::class, 'show']);
        Route::put('/{id}', [KitchenController::class, 'update']); 
        Route::delete('/{id}', [KitchenController::class, 'destroy']);
    });

    Route::prefix('kitchens/{kitchen_id}/meals')->group(function () {
        Route::get('/', [MealController::class, 'index']);       // Retrieve all meals for a kitchen
        Route::post('/', [MealController::class, 'store']);      // Create a new meal for a kitchen
        Route::put('/{meal_id}', [MealController::class, 'update']);  // Update an existing meal
        Route::delete('/{meal_id}', [MealController::class, 'destroy']); 
        Route::post('/{id}/uploadMealImage', [MealController::class, 'uploadMealImage']);// upload meal image
        
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('favorites/kitchens', [FavoriteKitchenController::class, 'index']); // Retrieve favorite kitchens
        Route::post('favorites/kitchens/{kitchenId}', [FavoriteKitchenController::class, 'store']); // Add kitchen to favorites
        Route::delete('favorites/kitchens/{kitchenId}', [FavoriteKitchenController::class, 'destroy']); // Remove kitchen from favorites
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('favorites/meals', [FavoriteMealController::class, 'index']); // Retrieve favorite meals
        Route::post('favorites/meals/{mealId}', [FavoriteMealController::class, 'store']); // Add meal to favorites
        Route::delete('favorites/meals/{mealId}', [FavoriteMealController::class, 'destroy']); // Remove meal from favorites
    });
    
    
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']); 
        Route::post('/add', [CartController::class, 'add']); 
        Route::put('/update/{meal_id}', [CartController::class, 'update']);
        Route::delete('/remove/{meal_id}', [CartController::class, 'destroy']);
        Route::delete('/remove', [CartController::class, 'clear']);
    });
    
    Route::prefix('orders')->group(function () {
        Route::post('/daily', [OrderController::class, 'storeDailyOrder']); 
        Route::post('/event', [OrderController::class, 'storeEventOrder']); 
    });
    
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationController::class, 'index']); 
        Route::post('/', [LocationController::class, 'store']); 
        Route::put('/{id}', [LocationController::class, 'update']); 
        Route::delete('/{id}', [LocationController::class, 'destroy']);
    });
    Route::get('meals/category', [MealController::class, 'getMealsByCategory']);

    

    
});

