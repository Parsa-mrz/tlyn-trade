<?php

namespace App\Services\Interfaces;

use App\Models\User;

/**
 * Interface AuthenticationServiceInterface
 *
 * Defines the contract for authentication-related operations such as login and registration.
 */
interface AuthenticationServiceInterface
{
    /**
     * Attempts to authenticate a user with the provided credentials.
     *
     * @param array $credentials An associative array containing login credentials (e.g., email and password).
     * @return array An array containing authentication data (e.g., user info, access token, etc.).
     */
    public function authenticate(array $credentials): array;

    /**
     * Registers a new user with the provided credentials.
     *
     * @param array $credentials An associative array containing registration data.
     * @return User The newly created User model instance.
     */
    public function register(array $credentials): User;
}
