<?php

namespace App\Services\Order\Commission;

/**
 * Interface CommissionStrategy
 *
 * Defines the contract for calculating commission based on weight and price.
 */
interface CommissionStrategy
{
    /**
     * Calculate the commission for a given order.
     *
     * @param float $weight The weight of the order in grams.
     * @param float $pricePerGram The price per gram of the order.
     *
     * @return float The calculated commission amount.
     */
    public function calculate(float $weight, float $pricePerGram): float;
}
