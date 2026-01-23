<?php

namespace App\Policies;

use App\Models\User;
use App\Models\course;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Anyone can view courses
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, course $course): bool
    {
        // Anyone can view a course
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only instructors and admins can create courses
        return $user->hasAnyRole(['instructor', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, course $course): bool
    {
        // Admins can update any course, instructors can only update their own
        return $user->hasRole('admin') || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, course $course): bool
    {
        // Admins can delete any course, instructors can only delete their own
        return $user->hasRole('admin') || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, course $course): bool
    {
        // Admins can restore any course, instructors can only restore their own
        return $user->hasRole('admin') || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, course $course): bool
    {
        // Only admins can permanently delete courses
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage sections for this course.
     */
    public function manageSection(User $user, course $course): bool
    {
        // Admins can manage any course's sections, instructors can only manage their own
        return $user->hasRole('admin') || $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can manage lectures for this course.
     */
    public function manageLecture(User $user, course $course): bool
    {
        // Admins can manage any course's lectures, instructors can only manage their own
        return $user->hasRole('admin') || $course->instructor_id === $user->id;
    }
}
