<?php

namespace App\Http\Controllers\Wallet;

use App\Helpers\ResponseHelper;
use App\Http\Resources\WalletResource;
use App\Models\User;
use App\Repositories\Interfaces\WalletRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

/**
 * Class WalletController
 *
 * Handles wallet-related operations for a given user.
 *
 * @package App\Http\Controllers\Wallet
 */
class WalletController
{
    use AuthorizesRequests;

    /**
     * The wallet repository instance.
     *
     * @var WalletRepositoryInterface
     */
    protected WalletRepositoryInterface $repository;

    /**
     * WalletController constructor.
     *
     * @param WalletRepositoryInterface $repository  The repository for accessing wallet data.
     */
    public function __construct(WalletRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show the wallet information of a specific user.
     *
     * Authorizes the request to ensure the authenticated user is allowed
     * to view the wallet, then returns the wallet data in a success response.
     *
     * @param User $user  The user whose wallet is being accessed.
     *
     * @return JsonResponse  The formatted response containing wallet data.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException  If the user is not authorized to view the wallet.
     */
    public function show(User $user): JsonResponse
    {
        $wallet = $this->repository->getWalletByUser($user);

        $this->authorize('view', $wallet);

        return ResponseHelper::success(
            'Wallet Retrieved Successfully',
            [
                'wallet' => new WalletResource($wallet),
            ]
        );
    }
}
