<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kitchen_id',
        'meal_name',
        'meal_description',
        'ingredients',
        'main_ingredient',
        'meal_image',
        'price',
        'meal_type',
        'category', 
        'discount', 
    ];

    protected $casts = [
        'ingredients' => 'array',
    ];

    // Relationship to kitchen
    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}
