<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $adminRoleId = Role::where('name', 'Admin')->first()->id;
        $authorRoleId = Role::where('name', 'Author')->first()->id;

        $users = [
            [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRoleId,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $authorRoleId,
            ],
            [
                'name' => 'Neha Sharma',
                'email' => 'nehasharma@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $authorRoleId,
            ],
            [
                'name' => 'Ravi Kumar',
                'email' => 'ravikumar@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $adminRoleId,
            ],
            [
                'name' => 'Aarav Patel',
                'email' => 'aaravpatel@gmail.com',
                'password' => Hash::make('password123'),
                'role_id' => $authorRoleId,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
