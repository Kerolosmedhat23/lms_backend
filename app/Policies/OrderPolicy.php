<?php

namespace App\Policies;

use App\Models\User;
use App\Models\order;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own orders, admins can view all
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, order $order): bool
    {
        // Users can view their own orders, admins can view any
        return $user->hasRole('admin') || $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Students can create orders
        return $user->hasRole('student');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, order $order): bool
    {
        // Users can update their own orders (status changes), admins can update any
        return $user->hasRole('admin') || $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, order $order): bool
    {
        // Only admins can delete orders
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, order $order): bool
    {
        // Only admins can restore orders
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, order $order): bool
    {
        // Only admins can permanently delete orders
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can complete the order.
     */
    public function complete(User $user, order $order): bool
    {
        // Users can complete their own orders, admins can complete any
        return $user->hasRole('admin') || $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, order $order): bool
    {
        // Users can cancel their own orders, admins can cancel any
        return $user->hasRole('admin') || $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can manage items in this order.
     */
    public function manageItems(User $user, order $order): bool
    {
        // Users can manage their own order items, admins can manage any
        return $user->hasRole('admin') || $order->user_id === $user->id;
    }
}
