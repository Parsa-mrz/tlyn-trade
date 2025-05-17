<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Interface OrderRepositoryInterface
 *
 * Defines the contract for interacting with orders in the repository layer.
 * Provides methods for creating, finding, updating, and marking orders.
 *
 * @package App\Repositories\Interfaces
 */
interface OrderRepositoryInterface
{
    /**
     * Create a new order in the database.
     *
     * @param array<string, mixed> $data The data used to create the order.
     * @return Order The created Order model instance.
     */
    public function create(array $data): Order;

    /**
     * Find open orders that match the given type and price.
     *
     * @param string $type The type of the order (e.g., buy or sell).
     * @param float $price The price to match orders against.
     * @return Collection The collection of orders that match the given criteria.
     */
    public function findOpenMatches(string $type, float $price): Collection;

    /**
     * Update the remaining weight of an order.
     *
     * @param Order $order The order to update.
     * @param float $newWeight The new remaining weight of the order.
     * @return void
     */
    public function updateRemainingWeight(Order $order, float $newWeight): void;

    /**
     * Get all orders associated with a specific user.
     *
     * @param User $user The user whose orders should be retrieved.
     * @return Collection<int, Order> A collection of the user's orders.
     */
    public function getAllOrders(User $user): Collection;
}
