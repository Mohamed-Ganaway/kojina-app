<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'phone_number', 'password', 'favorites_kitchens', 'favorites_meals',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * JWTSubject Methods
     */
     
     protected $casts = [
        'favorites_kitchens' => 'array',
        'favorites_meals' => 'array',
    ];

    // 1. Get the identifier that will be stored in the JWT (usually the user's id)
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // 2. Return a key value array, containing any custom claims to be added to the JWT
    public function getJWTCustomClaims()
    {
        return [];
    }
}
