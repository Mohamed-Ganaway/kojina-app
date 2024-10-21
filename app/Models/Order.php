<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'location_id', 'type', 'status'];

    // Each order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each order belongs to a location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // An order can have many meals (many-to-many relationship)
    public function meals()
    {
        return $this->belongsToMany(Meal::class)->withPivot('quantity');
    }
}
