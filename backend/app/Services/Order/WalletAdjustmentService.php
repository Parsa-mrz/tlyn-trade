<?php

namespace App\Services\Order;

use App\Models\User;
use App\Models\Wallet;

/**
 * Class WalletAdjustmentService
 *
 * Handles wallet balance adjustments after a successful order match between buyer and seller.
 */
class WalletAdjustmentService
{
    /**
     * Adjusts the wallets of the buyer and the seller based on transaction details.
     *
     * - The buyer's rial balance is reduced by (amount + commission).
     * - The buyer's gold balance is increased by the purchased weight.
     * - The seller's rial balance is increased by (amount - commission).
     * - The seller's gold balance is reduced by the sold weight.
     *
     * @param User  $buyer      The user who is buying the gold.
     * @param User  $seller     The user who is selling the gold.
     * @param float $amount     The total amount of rial for the transaction (without commission).
     * @param float $weight     The weight of gold being exchanged.
     * @param float $commission The commission fee applied to the transaction.
     *
     * @return void
     */
    public function adjust(User $buyer, User $seller, float $amount, float $weight, float $commission): void
    {
        /** @var Wallet $buyerWallet */
        $buyerWallet = $buyer->wallet;

        /** @var Wallet $sellerWallet */
        $sellerWallet = $seller->wallet;

        $buyerWallet->rial_balance -= ($amount + $commission);
        $buyerWallet->gold_balance += $weight;

        $sellerWallet->rial_balance += ($amount - $commission);
        $sellerWallet->gold_balance -= $weight;

        $buyerWallet->save();
        $sellerWallet->save();
    }
}
