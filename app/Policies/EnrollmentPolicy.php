<?php

namespace App\Policies;

use App\Models\User;
use App\Models\enrollment;
use Illuminate\Auth\Access\Response;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own enrollments
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, enrollment $enrollment): bool
    {
        // Users can view their own enrollments, instructors can view their course enrollments, admins can view any
        return $user->hasRole('admin') 
            || $enrollment->user_id === $user->id
            || ($user->hasRole('instructor') && $enrollment->course->instructor_id === $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Enrollments are created automatically through order completion
        // But admins can manually create enrollments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, enrollment $enrollment): bool
    {
        // Only admins can update enrollments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, enrollment $enrollment): bool
    {
        // Users can delete their own enrollments, admins can delete any
        return $user->hasRole('admin') || $enrollment->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, enrollment $enrollment): bool
    {
        // Only admins can restore enrollments
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, enrollment $enrollment): bool
    {
        // Only admins can permanently delete enrollments
        return $user->hasRole('admin');
    }
}
