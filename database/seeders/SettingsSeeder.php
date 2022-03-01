<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'name' => "name",
            'logo' => 'default.png',
            'icon' => 'default.png',
            'description' => 'description',
            'status' => 'open',
            'alt_text' => 'open',
        ]);
    }
}
