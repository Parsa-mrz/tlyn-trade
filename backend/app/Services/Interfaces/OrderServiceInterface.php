<?php

namespace App\Services\Interfaces;

use App\DTOs\OrderDTO;
use App\Models\Order;
use App\Models\User;

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
    public function placeOrder(OrderDTO $dto): Order;
}
