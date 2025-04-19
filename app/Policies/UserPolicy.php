<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view($user_id, $id): Response
    {
        // Check if the user is authenticated and has the same ID as the requested user
        if ($user_id == $id) {
            // If the user is authorized, allow access
            return Response::allow();
        }

        // If the user is not authorized, deny access
        return Response::deny('You can not view this user.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, User $targetUser): Response
    {
        return $authUser->id === $targetUser->id
            ? Response::allow()
            : Response::deny('You can not edit this user.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $targetUser): Response
    {
        return $authUser->id === $targetUser->id
            ? Response::allow()
            : Response::deny('You can not delete this user.');
    }
}
