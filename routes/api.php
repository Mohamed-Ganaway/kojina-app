<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\MealController;



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){


    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    
 
    
    Route::prefix('kitchens')->group(function () {
        Route::get('/', [KitchenController::class, 'index']); 
        Route::post('/', [KitchenController::class, 'store']); 
        Route::put('/{id}', [KitchenController::class, 'update']); 
        Route::delete('/{id}', [KitchenController::class, 'destroy']);
    });
    
    Route::prefix('kitchens/{kitchen_id}/meals')->group(function () {
        Route::get('/', [MealController::class, 'index']); 
        Route::post('/', [MealController::class, 'store']); 
        Route::put('/{meal_id}', [MealController::class, 'update']);
        Route::delete('/{meal_id}', [MealController::class, 'destroy']);
    });
    
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']); 
        Route::post('/add', [CartController::class, 'add']); 
        Route::put('/update/{meal_id}', [CartController::class, 'update']);
        Route::delete('/remove/{meal_id}', [CartController::class, 'destroy']);
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
    
});

