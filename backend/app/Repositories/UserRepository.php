<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user record.
     *
     * @param  array  $data  An associative array of user data.
     * @return User The newly created User instance.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by their email address.
     *
     * @param  string  $email  The email address to search for.
     * @return User|null The User instance if found, or null if not found.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by their ID.
     *
     * @param  int  $id  The ID of the user.
     * @return User|null The User instance if found, or null if not found.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}
