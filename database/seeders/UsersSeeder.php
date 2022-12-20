<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "name" => "Admin",
                "email" => "admin@gmail.com",
                "password" => Hash::make("12345"),
                "role_id" => 1,
            ],
            [
                "name" => "Manager",
                "email" => "manager@gmail.com",
                "password" => Hash::make("12345"),
                "role_id" => 2,
            ],
            [
                "name" => "User",
                "email" => "user@gmail.com",
                "password" => Hash::make("12345"),
                "role_id" => 3,
            ],
        ];

        User::insert($data);
    }
}
