<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

/**
 * Interface UserRepositoryInterface
 *
 * Contract for user repository handling user-related data operations.
 */
interface UserRepositoryInterface
{
    /**
     * Create a new user record.
     *
     * @param  array  $data  An associative array of user data.
     * @return User The newly created User instance.
     */
    public function create(array $data): User;

    /**
     * Find a user by their email address.
     *
     * @param  string  $email  The email address to search for.
     * @return User|null The User instance if found, or null if not found.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by their ID.
     *
     * @param  int  $id  The ID of the user.
     * @return User|null The User instance if found, or null if not found.
     */
    public function findById(int $id): ?User;
}
