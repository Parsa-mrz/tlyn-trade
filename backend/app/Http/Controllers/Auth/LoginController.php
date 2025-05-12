<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthenticateService;

/**
 * Class LoginController
 *
 * Handles user authentication (login).
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController
{
    /**
     * LoginController constructor.
     *
     * @param AuthenticateService $authenticateService The service responsible for authenticating users.
     */
    public function __construct(
        protected AuthenticateService $authenticateService,
    ) {}

    /**
     * Handle a login request.
     *
     * Authenticates the user and returns a success response with token and user info.
     *
     * @param UserLoginRequest $request The validated login request.
     * @return \Illuminate\Http\JsonResponse The success response containing token and user data.
     */
    public function login(UserLoginRequest $request)
    {
        $result = $this->authenticateService->authenticate($request->validated());

        return ResponseHelper::success(
            'Logged in successfully',
            [
                'token' => $result['access_token'],
                'user' => new UserResource($result['user']),
            ]
        );
    }
}
