<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Interfaces\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{

    /**
     * Retrieve a wallet instance by the given user ID.
     *
     * @param User $user The Instance of the user whose wallet is being retrieved.
     * @return Wallet The wallet associated with the user.
     */
    public function getWalletByUser(User $user): Wallet
    {
        return $user->wallet;
    }
}
