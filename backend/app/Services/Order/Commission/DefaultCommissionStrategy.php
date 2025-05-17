<?php

namespace App\Services\Order\Commission;

/**
 * Class DefaultCommissionStrategy
 *
 * Implements a default commission calculation based on weight tiers.
 */
class DefaultCommissionStrategy implements CommissionStrategy
{
    /**
     * Calculate the commission based on weight and price per gram.
     *
     * Commission rate:
     * - 1g or less: 1.5%
     * - >1g and <=2g: 1.0%
     * - >2g: 0.5%
     *
     * Commission is clamped between 500,000 and 50,000,000.
     *
     * @param float $weight The weight of the order in grams.
     * @param float $pricePerGram The price per gram.
     *
     * @return float The calculated commission amount, bounded within allowed limits.
     */
    public function calculate(float $weight, float $pricePerGram): float
    {
        $rate = match (true) {
            $weight <= 1 => 0.015,
            $weight <= 2 => 0.01,
            default => 0.005,
        };

        $commission = $rate * ($weight * $pricePerGram);

        return min(max($commission, 500000), 50000000);
    }
}
