<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use function rand;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'user1',
                'email' => 'user1@domain.com',
                'password' => 'password',
                'gold_balance' => rand(0, 100),
                'rial_balance' => rand(1_000_000, 10_000_000),
            ],
            [
                'name' => 'user2',
                'email' => 'user2@domain.com',
                'password' => 'password',
                'gold_balance' => rand(0, 100),
                'rial_balance' => rand(1_000_000, 10_000_000),
            ],
            [
                'name' => 'user3',
                'email' => 'user3@domain.com',
                'password' => 'password',
                'gold_balance' => rand(0, 100),
                'rial_balance' => rand(1_000_000, 10_000_000),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            $user->wallet()->updateOrCreate([
                'gold_balance' => $userData['gold_balance'],
                'rial_balance' => $userData['rial_balance'],
            ]);
        }

    }
}
