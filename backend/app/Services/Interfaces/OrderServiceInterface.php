<?php

namespace App\Services\Interfaces;

use App\Models\Order;

/**
 * Interface OrderServiceInterface
 *
 * Defines the contract for order-related business logic services.
 */
interface OrderServiceInterface
{
    /**
     * Places a new order based on the provided data.
     *
     * @param array $data The data required to create and process the order.
     * @return Order The newly created Order instance.
     */
    public function placeOrder(array $data): Order;
}
