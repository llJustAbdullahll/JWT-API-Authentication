<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email','password']);
            $token = Auth::attempt($credentials);

            if (!$token){
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();

            return response()->json([
               'user' => $user,
               'token' => $token
            ]);

        }catch (\Exception){
           return response()->json(['message' => 'Something went wrong' ], 500);        
        }
    }

    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user) {
            $token = Auth::login($user);
            
            return response()->json(['user' => $user, 'token' => $token], 201);
        }
    
        return response()->json(['message' => 'Registration failed'], 500);
    }
}
