<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //

    public function register(Request $request){

        try {

            $data = $request->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:8'],
            ]);
    
            $user = User::create($data);
            
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'user' => $user,
                'token' => $token,
            ]);
    
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }

    public function login(Request $request){

        try {

            $data = $request->validate([
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required', 'min:8'],
            ]);
    
            $user = User::where('email', $data['email'])->first();
    
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'user' => $user,
                'token' => $token,
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An unexpected error occurred during logout',
            ], 500);
        }
    }
}
