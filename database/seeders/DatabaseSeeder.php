<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LaratrustSeeder::class,
            SettingsSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            PharmacySeeder::class,
            TimetableSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
