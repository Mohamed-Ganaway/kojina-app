<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relationship with user
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // Relationship with location
            $table->string('type'); // Ensure this exists and is set to NOT NULL
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending'); // Order status
            $table->timestamps();
        });

        // Pivot table for orders and meals
        Schema::create('meal_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_order');
        Schema::dropIfExists('orders');
    }
};
