<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => bcrypt('password123'),
                'role_id' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@gmail.com',
                'password' => bcrypt('password123'),
                'role_id' => 2,
            ],
            [
                'name' => 'Neha Sharma',
                'email' => 'nehasharma@gmail.com',
                'password' => bcrypt('password123'),
                'role_id' => 2,
            ],
            [
                'name' => 'Ravi Kumar',
                'email' => 'ravikumar@gmail.com',
                'password' => bcrypt('password123'),
                'role_id' => 1,
            ],
            [
                'name' => 'Aarav Patel',
                'email' => 'aaravpatel@gmail.com',
                'password' => bcrypt('password123'),
                'role_id' => 2,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
