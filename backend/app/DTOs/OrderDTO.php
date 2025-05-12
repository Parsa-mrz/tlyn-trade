<?php

namespace App\DTOs;

/**
 * Data Transfer Object for creating an Order.
 */
class OrderDTO
{
    /**
     * Create a new OrderDTO instance.
     *
     * @param int $user_id The ID of the user placing the order.
     * @param string $type The type of order ('buy' or 'sell').
     * @param float $weight The weight of the gold in grams.
     * @param float $price_per_gram The price per gram of gold in Rial.
     */
    public function __construct(
        public readonly int $user_id,
        public readonly string $type,
        public readonly float $weight,
        public readonly float $price_per_gram
    ) {}
}
