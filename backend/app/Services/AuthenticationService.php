<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthenticationServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthenticationService
 *
 * Handles user authentication and registration logic.
 */
class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * AuthenticationService constructor.
     *
     * @param  UserRepositoryInterface  $userRepository  Repository for accessing user data.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * @param  array<string, string>  $credentials  Array containing 'email' and 'password'.
     * @return array{access_token: string, user: User} The authenticated user and their access token.
     *
     * @throws AuthenticationException If authentication fails.
     */
    public function authenticate(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
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
     * @param  array<string, mixed>  $credentials  User data, typically including 'email' and 'password'.
     * @return User The newly created user.
     */
    public function register(array $credentials): User
    {
        $user = $this->userRepository->create($credentials);
        $user->wallet()->create();

        return $user;
    }
}
