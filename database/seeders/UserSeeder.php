<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::create([
            "name" => "user",
            "email" => "user@gmail.com",
            "password" => bcrypt(123123)
        ]);
    }
}
