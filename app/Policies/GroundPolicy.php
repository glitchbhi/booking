<?php

namespace App\Policies;

use App\Models\Ground;
use App\Models\User;

class GroundPolicy
{
    /**
     * Determine if the user can view the ground
     */
    public function view(User $user, Ground $ground): bool
    {
        // Everyone can view active grounds
        if ($ground->is_active) {
            return true;
        }

        // Owner can view their own grounds
        if ($user->isOwner() && $ground->owner_id === $user->id) {
            return true;
        }

        // Admin can view all grounds
        return $user->isAdmin();
    }

    /**
     * Determine if the user can create grounds
     */
    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the ground
     */
    public function update(User $user, Ground $ground): bool
    {
        // Owner can update their own grounds
        if ($user->isOwner() && $ground->owner_id === $user->id) {
            return true;
        }

        // Admin can update all grounds
        return $user->isAdmin();
    }

    /**
     * Determine if the user can delete the ground
     */
    public function delete(User $user, Ground $ground): bool
    {
        // Only admin can delete grounds
        return $user->isAdmin();
    }
}
