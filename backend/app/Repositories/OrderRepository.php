<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class OrderRepository
 *
 * Implements the OrderRepositoryInterface for interacting with orders in the database.
 * Provides methods for creating, finding, updating, and marking orders.
 *
 * @package App\Repositories
 */
class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Create a new order in the database.
     *
     * @param array<string, mixed> $data The data used to create the order.
     * @return Order The created Order model instance.
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Find open orders that match the given type and price.
     *
     * @param string $type The type of the order (e.g., buy or sell).
     * @param float $price The price to match orders against.
     * @return Collection A collection of orders that match the given type, price, and are open or partially filled.
     */
    public function findOpenMatches(string $type, float $price): Collection
    {
        $oppositeType = $type === 'buy' ? 'sell' : 'buy';
        return Order::where('type', $oppositeType)
                    ->where('price_per_gram', $price)
                    ->whereIn('status', ['open', 'partially_filled'])
                    ->orderBy('created_at')
                    ->get();
    }

    /**
     * Update the remaining weight of an order.
     *
     * @param Order $order The order to update.
     * @param float $newWeight The new remaining weight of the order.
     * @return void
     */
    public function updateRemainingWeight(Order $order, float $newWeight): void
    {
        $order->remaining_weight = $newWeight;
        $order->status = $newWeight == 0 ? 'filled' : 'partially_filled';
        $order->save();
    }

    /**
     * Get all orders associated with a specific user.
     *
     * @param User $user The user whose orders should be retrieved.
     * @return Collection<int, Order> A collection of the user's orders.
     */
    public function getAllOrders(User $user): Collection
    {
        return $user->orders()->with('transactions')->orderBy('created_at')->get();
    }

}
