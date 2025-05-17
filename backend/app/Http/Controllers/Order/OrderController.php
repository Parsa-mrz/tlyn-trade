<?php

namespace App\Http\Controllers\Order;

use App\DTOs\OrderDTO;
use App\Exceptions\InsufficientBalanceException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 *
 * Handles HTTP requests related to user orders, such as creating and listing them.
 *
 * @package App\Http\Controllers\Order
 */
class OrderController
{
    use AuthorizesRequests;
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
     * @param OrderRepositoryInterface $orderRepository Repository to interact with orders in the database.
     */
    public function __construct(
        protected OrderServiceInterface $orderService,
        protected OrderRepositoryInterface $orderRepository
    ) {
    }

    /**
     * Display a listing of the authenticated user's orders.
     *
     * @return JsonResponse JSON response containing order data or an error message.
     */
    public function index ()
    {
        /**@var User $user */
        $user = Auth::user();

        $orders = $this->orderRepository->getAllOrders($user);
        if($orders->isEmpty()) {
            return ResponseHelper::error ('No orders found',null,Response::HTTP_NOT_FOUND);
        }
        return ResponseHelper::success (
            'Orders Retrieved Successfully',
            OrderResource::collection($orders)
        );
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
            return ResponseHelper::success('Order created successfully', new OrderResource($order));
        } catch (InsufficientBalanceException $e) {
            return ResponseHelper::error($e->getMessage(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return ResponseHelper::error('Unexpected error occurred', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Cancel a given order after checking authorization.
     *
     * Only the user who placed the order or an authorized admin can cancel it.
     *
     * @param Order $order The order model bound via route-model binding.
     * @return JsonResponse JSON response indicating the result of the cancellation.
     */
    public function cancel (Order $order): JsonResponse
    {
        $this->authorize('cancel', $order);
        $this->orderRepository->update ($order,[
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return ResponseHelper::success('Order cancelled successfully');
    }
}
