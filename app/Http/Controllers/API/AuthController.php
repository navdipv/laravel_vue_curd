<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->success([], 'User registered successfully.', 201);
        } catch (\Exception $e) {
            return $this->error('User registration failed: ' . $e->getMessage(), 400);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('The provided credentials are incorrect.', 401);
            }

            return $this->success([
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ], 'Login successful.');
        } catch (\Exception $e) {
            return $this->error('Login failed: ' . $e->getMessage(), 400);
        }
    }

    public function getProfile(Request $request){

        try {

            $user = $request->user();

            return $this->success([
                'user' => $user
            ], '');
        } catch (\Exception $e) {
            return $this->error('Login failed: ' . $e->getMessage(), 401);
        }
    }

    public function logout(Request $request){

        try {

            $user = $request->user();
            $user->tokens()->delete();

            return $this->success([

            ], 'User logged out successfully.');
        } catch (\Exception $e) {
            return $this->error('Login failed: ' . $e->getMessage(), 401);
        }
    }
}
