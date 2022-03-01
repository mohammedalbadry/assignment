<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "password" => bcrypt(123123)
        ]);
        $admin->attachRole("manager");
        $admin->attachPermissions(Role::find(1)->permissions);

    }
}
