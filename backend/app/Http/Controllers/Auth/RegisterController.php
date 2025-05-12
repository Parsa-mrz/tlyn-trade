<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthenticationService;
use App\Services\Interfaces\AuthenticationServiceInterface;

/**
 * Class RegisterController
 *
 * Handles user registration requests.
 */
class RegisterController extends Controller
{
    /**
     * RegisterController constructor.
     *
     * @param  AuthenticationServiceInterface  $authenticateService  The service responsible for user registration and authentication.
     */
    public function __construct(
        protected AuthenticationServiceInterface $authenticateService,
    ) {}

    /**
     * Handle a user registration request.
     *
     * Registers the user and returns a formatted response with user data.
     *
     * @param  UserRegisterRequest  $request  The validated registration request.
     * @return \Illuminate\Http\JsonResponse A success response containing the registered user data.
     */
    public function register(UserRegisterRequest $request)
    {
        $user = $this->authenticateService->register($request->validated());

        return ResponseHelper::success('Registered Successfully', new UserResource($user));
    }
}
