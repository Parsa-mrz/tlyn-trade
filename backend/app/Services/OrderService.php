<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService
 *
 * Responsible for handling operations related to placing and matching orders.
 */
class OrderService implements OrderServiceInterface
{
    /**
     * Constructor for the OrderService.
     *
     * @param OrderRepositoryInterface $orderRepository The repository instance for order operations.
     */
    public function __construct
    (
        protected OrderRepositoryInterface $orderRepository
    )
    {
    }

    /**
     * Places a new order and attempts to match it with existing open orders.
     * This is executed within a database transaction.
     *
     * @param array $data The data used to create the order.
     * @return Order The newly created order with updated remaining weight.
     */
    public function placeOrder (array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = $this->orderRepository->create($data);
            $remainingWeight = $order->remaining_weight;

            $matches = $this->orderRepository->findOpenMatches($order->type, $order->price_per_gram);

            foreach ($matches as $match) {
                if ($remainingWeight <= 0) break;

                $matchedWeight = min($remainingWeight, $match->remaining_weight);
                $totalPrice = $matchedWeight * $order->price_per_gram;

                $commission = $this->calculateCommission($matchedWeight);

                $this->adjustWallets();

                // TODO: create transaction

                $remainingWeight -= $matchedWeight;
                $this->orderRepository->updateRemainingWeight($match, $match->remaining_weight - $matchedWeight);
            }

            $this->orderRepository->updateRemainingWeight($order, $remainingWeight);

            return $order;
        });
    }

    /**
     * Calculates the commission based on the matched weight.
     *
     * @param float $weight The weight for which the commission is calculated.
     * @return float The calculated commission value.
     */
    private function calculateCommission(float $weight): float
    {
        return match (true) {
                   $weight <= 2   => 0.015,
                   $weight <= 10  => 0.01,
                   default        => 0.005,
               } * $weight;
    }

    private function adjustWallets(): void
    {
        // TODO: update wallet
    }

}
