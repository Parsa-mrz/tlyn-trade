<?php

namespace App\Http\Controllers\Order;

use App\DTOs\OrderDTO;
use App\Exceptions\InsufficientBalanceException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreOrderRequest;
use App\Models\User;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 *
 * Handles HTTP requests related to orders.
 */
class OrderController
{
    /**
     * Default gold price per gram.
     *
     * @var int
     */
    protected $goldPrice = 10000000;

    /**
     * OrderController constructor.
     *
     * @param OrderServiceInterface $orderService Service to handle order operations.
     */
    public function __construct(
        protected OrderServiceInterface $orderService
    ) {
    }

    /**
     * Store a new order for the authenticated user.
     *
     * Validates user balance before placing the order and handles any
     * exceptions that may occur during the process.
     *
     * @param StoreOrderRequest $request The request containing validated order input data.
     *
     * @return JsonResponse JSON response indicating success or failure.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        /**@var User $user */
        $user = Auth::user();

        $dto = new OrderDTO(
            user_id: $user->id,
            type: $request->type,
            weight: $request->weight,
            price_per_gram: $this->goldPrice
        );


        try {
            $this->orderService->validateUserBalance($dto);
            $order = $this->orderService->placeOrder($dto);
            return ResponseHelper::success('Order created successfully', $order);
        } catch (InsufficientBalanceException $e) {
            return ResponseHelper::error($e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return ResponseHelper::error('Unexpected error occurred', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
