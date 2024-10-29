<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth; 

class AuthController extends Controller
{
    public function register(Request $request) {

        // Validate request input
        $input = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'unique:users,phone_number', 'min:9', 'max:10'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Create new user
        $user = User::create([
            'full_name' => $input['full_name'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return response()->json([
            'message' => 'User created successfully',
        ], 201);
    }
    
    public function login(Request $request){
        
       
        $credentials = $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {  
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request){
        auth()->logout(); 
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }


    public function user(){
        $user = auth()->user(); 
        return response()->json([
            'user_data' => $user
        ]);
    }

    public function refresh()
{
    $newToken = auth()->refresh(true,true);

    return response()->json([
        'access_token' => $newToken,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
    ]);
}

}
