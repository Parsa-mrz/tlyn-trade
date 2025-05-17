<?php

namespace App\Services\Order;

use App\DTOs\OrderDTO;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Order\Commission\CommissionStrategy;
use Illuminate\Support\Facades\DB;

/**
 * Service responsible for handling order placement and validation logic.
 */
class OrderService implements OrderServiceInterface
{
    /**
     * @param OrderRepositoryInterface $orderRepository Repository for order data access.
     * @param WalletAdjustmentService $walletAdjustmentService Service to adjust buyer/seller wallets after trade.
     * @param CommissionStrategy $commissionStrategy Strategy to calculate commission fees on trades.
     */
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected WalletAdjustmentService $walletAdjustmentService,
        protected CommissionStrategy $commissionStrategy
    ) {}

    /**
     * Places a new order and attempts to match it with existing opposite orders.
     *
     * This method is transactional and ensures the buyer/seller wallets are updated accordingly,
     * remaining weights are adjusted, and order statuses are updated.
     *
     * @param OrderDTO $dto Data transfer object containing order details.
     * @return Order The created order after all matching and updates.
     * @throws \Throwable If any exception occurs during transaction.
     */
    public function placeOrder(OrderDTO $dto): Order
    {
        return DB::transaction(function () use ($dto) {
            $data = [
                'user_id' => $dto->user_id,
                'type' => $dto->type,
                'weight' => $dto->weight,
                'price_per_gram' => $dto->price_per_gram,
                'remaining_weight' => $dto->weight,
            ];

            $order = $this->orderRepository->create($data);

            $remainingWeight = $order->remaining_weight;

            $matches = $this->orderRepository->findOpenMatches(
                $order->type,
                $order->price_per_gram
            );

            foreach ($matches as $match) {
                if ($remainingWeight <= 0) break;

                $matchedWeight = min($remainingWeight, $match->remaining_weight);
                $price = $order->price_per_gram;
                $totalPrice = $matchedWeight * $price;

                $commission = $this->commissionStrategy->calculate($matchedWeight, $price);

                $this->walletAdjustmentService->adjust(
                    buyer: $order->type === 'buy' ? $order->user : $match->user,
                    seller: $order->type === 'sell' ? $order->user : $match->user,
                    amount: $totalPrice,
                    weight: $matchedWeight,
                    commission: $commission
                );

                Transaction::create([
                    'buyer_id'        => $order->type === 'buy' ? $order->user_id : $match->user_id,
                    'seller_id'       => $order->type === 'sell' ? $order->user_id : $match->user_id,
                    'order_id'        => $order->id,
                    'match_order_id'  => $match->id,
                    'weight'          => $matchedWeight,
                    'price_per_gram'  => $price,
                    'total_price'     => $totalPrice,
                    'commission'      => $commission,
                ]);

                $this->orderRepository->updateRemainingWeight($match, $match->remaining_weight - $matchedWeight);
                $match->refresh();
                $this->updateOrderStatus($match);

                $remainingWeight -= $matchedWeight;
            }

            $this->orderRepository->updateRemainingWeight($order, $remainingWeight);
            $order->refresh();
            $this->updateOrderStatus($order);

            return $order;
        });
    }

    /**
     * Validates whether the user has enough balance (rial or gold) to place an order.
     *
     * @param OrderDTO $dto Data transfer object containing order details.
     * @return void
     * @throws InsufficientBalanceException If user does not have enough funds to proceed.
     * @throws \InvalidArgumentException If order type is invalid.
     */
    public function validateUserBalance(OrderDTO $dto): void
    {
        $user = User::with('wallet', 'orders')->findOrFail($dto->user_id);
        $wallet = $user->wallet;

        if (!$wallet) {
            throw new InsufficientBalanceException('Wallet not found for user.');
        }

        $totalCost = $dto->weight * $dto->price_per_gram;

        if ($dto->type === 'buy') {
            if ($wallet->rial_balance < $totalCost) {
                throw new InsufficientBalanceException('Not enough rial to buy');
            }
        } elseif ($dto->type === 'sell') {
            $reservedGold = $user->orders()
                                 ->where('type', 'sell')
                                 ->whereIn('status', ['open', 'partially_filled'])
                                 ->sum('remaining_weight');

            $availableGold = $wallet->gold_balance - $reservedGold;

            if ($availableGold < $dto->weight) {
                throw new InsufficientBalanceException('Not enough gold to sell (after considering existing orders)');
            }
        } else {
            throw new \InvalidArgumentException('Invalid order type');
        }
    }

    /**
     * Updates the status of an order based on its remaining weight.
     *
     * @param Order $order The order to update.
     * @return void
     */
    private function updateOrderStatus(Order $order): void
    {
        if ($order->remaining_weight == $order->weight) {
            $order->status = 'open';
        } elseif ($order->remaining_weight == 0) {
            $order->status = 'filled';
        } else {
            $order->status = 'partially_filled';
        }

        $order->save();
    }
}
