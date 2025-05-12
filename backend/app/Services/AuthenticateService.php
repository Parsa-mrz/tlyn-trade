<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/**
 * Class AuthenticateService
 *
 * Handles user authentication and registration logic.
 *
 * @package App\Services
 */
class AuthenticateService
{
    /**
     * AuthenticateService constructor.
     *
     * @param UserRepository $userRepository Repository for accessing user data.
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * @param array<string, string> $credentials Array containing 'email' and 'password'.
     * @return array{access_token: string, user: User} The authenticated user and their access token.
     *
     * @throws AuthenticationException If authentication fails.
     */
    public function authenticate(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $user = $this->userRepository->findByEmail($credentials['email']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Register a new user with the given credentials.
     *
     * @param array<string, mixed> $credentials User data, typically including 'email' and 'password'.
     * @return User The newly created user.
     */
    public function register(array $credentials): User
    {
        return $this->userRepository->create($credentials);
    }
}
