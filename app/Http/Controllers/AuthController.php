<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a login method to authenticate users.
     *
     * @return void
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|unique:users,password',
        ]);

        // If the credentials are invalid, return an error response
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        // If authentication is successful, get the user
        // $user = User::where('email', $request->email)->first();
        $user = auth()->userOrFail();

        // Check if the user password is valid
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'status' => '401',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Return the user and token
        return response()->json([
            'status' => 'success',
            'user' => $user->only('name', 'email'),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ], 202);
    }

    /**
     * Create a register method to register users.
     *
     * @return void
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a token for the user
        $token = auth()->login($user);;

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    /**
     * Create a logout method to logout users.
     *
     * @return void
     */
    public function logout()
    {
        // Logout the user
        Auth::logout();

        // Invalidate the token
        auth()->invalidate();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }

    /**
     * Create a me method to get the authenticated user.
     *
     * @return void
     */
    public function me()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Return the user as a JSON response
        return response()->json([
            'status' => 'success',
            'user' => $user->only('name', 'email'),
        ], 200);
    }

    /**
     * Create a refresh method to refresh the JWT token.
     *
     * @return void
     */
    public function refresh()
    {
        // Refresh the token
        return response()->json([
            'status' => 'success',
            'user' => auth()->user(),
            'authorization' => [
                'token' => auth()->refresh(),
                'type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
