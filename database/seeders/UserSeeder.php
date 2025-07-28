<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::firstOrCreate([
            'name' => 'Organizer',
            'email' => 'organizer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'organizer'
        ]);

        User::firstOrCreate([
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);

        User::firstOrCreate([
            'name' => 'User2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]);
    }
}
