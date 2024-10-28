<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kitchen_name',
        'phone_number',
        'email',
        'profile_image',
        'cover_image',
        'location',
        'categories',
        'opening_time',
        'closing_time',
        'rating',
    ];

    protected $casts = [
        'categories' => 'array', 
    ];

    // Relationship to meals
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorite_kitchens');
}

}
