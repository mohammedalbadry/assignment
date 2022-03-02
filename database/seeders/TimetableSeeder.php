<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Timetable;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) { 
            $timetable = Timetable::create([
                "title" => "event_title",
                "user_id" => 1,
                "pharmacy_id" => 1,
                "start_time" => Carbon::now()->addHours($i + 10),
                "end_time" => Carbon::now()->addHours($i*12),
            ]);
        }
    }
}
