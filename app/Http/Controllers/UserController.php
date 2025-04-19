<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all users
        $users = User::get();
        // Return the users as a JSON response
        return response()->json(
            [
                'status' => 'success',
                'data' => $users,
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Retrieve the user by ID
        $user = User::find($request->id);

        // If user not found, return an error response
        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                ],
                404
            );
        }

        // Return the user as a JSON response
        return response()->json(
            [
                'status' => 'success',
                'data' => $user,
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Retrieve the user by ID
        $authUser = Auth::user();
        $targetUser = User::find($request->id);

        // Check user authorization
        Gate::authorize('update', $authUser, $targetUser);

        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // If password is provided, hash it
        if (isset($fields['password'])) {
            $user->password = bcrypt($fields['password']);
        }

        // Update the user
        $user->update($fields);

        // Return the updated user as a JSON response
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User updated successfully',
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // Retrieve the authenticated user
        $authUser = Auth::user();
        // Retrieve the target user by ID
        $targetUser = User::find($request->id);

        // Check user authorization
        Gate::authorize('delete', $authUser, $targetUser);

        // If user not found, return an error response
        if (!$targetUser) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found',
                ],
                404
            );
        }

        // Delete the user
        $targetUser->delete();

        // Return a success response
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User deleted successfully',
            ],
            200
        );
    }
}
