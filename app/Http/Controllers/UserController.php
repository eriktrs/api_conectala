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
        // Determine the number of users to display per page
        $perPage = min(request()->input('per_page', 10), 100);

        // Determine the sorting criteria
        $sortBy = request()->input('sort_by', 'id');
        $sortOrder = request()->input('sort_order', 'asc');

        // Build the query
        $query = User::query();

        // Apply filters based on request parameters
        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request()->input('name') . '%');
        }

        if (request()->filled('email')) {
            $query->where('email', 'like', '%' . request()->input('email') . '%');
        }

        if ($sortBy && in_array($sortBy, ['id', 'name', 'email'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Retrieve all users
        $users = User::paginate($perPage);

        // Return the users as a JSON response
        return response()->json(
            [
                'status' => 'success',
                'data' => $users->items(),
                'pagination' => [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'next_page_url' => $users->nextPageUrl(),
                    'prev_page_url' => $users->previousPageUrl(),
                ],
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
        $authUser = Auth::user();
        $targetUser = User::find($request->id);

        // Check user authorization
        Gate::authorize('view', $authUser, $targetUser);

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
