<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine if the user can cancel the order.
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->status === 'open' || $order->status === 'partially_filled';
    }
}
