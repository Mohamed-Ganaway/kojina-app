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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_id')->constrained('kitchens')->onDelete('cascade'); // Foreign key to link the kitchen
            $table->string('meal_name');              
            $table->text('meal_description')->nullable();         
            $table->json('ingredients');              
            $table->string('main_ingredient');       
            $table->string('meal_image')->nullable(); 
            // may make a issue with the price on the frontend
            $table->decimal('price', 8, 2);          
            $table->enum('meal_type', ['Event Meal', 'Daily Menu Meal', 'Both']); 
            $table->String('category');
            $table->decimal('discount', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
