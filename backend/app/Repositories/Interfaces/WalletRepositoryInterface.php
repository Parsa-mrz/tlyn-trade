<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\Wallet;

/**
 * Interface WalletRepositoryInterface
 *
 * Defines the contract for wallet-related data access.
 */
interface WalletRepositoryInterface
{
    /**
     * Retrieve a wallet instance by the given user ID.
     *
     * @param User $user The Instance of the user whose wallet is being retrieved.
     * @return Wallet The wallet associated with the user.
     */
    public function getWalletByUser(User $user): Wallet;
}
