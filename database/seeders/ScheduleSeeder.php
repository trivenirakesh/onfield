<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daysOfWeek = Schedule::DAYS;
        for($i=0;$i<count($daysOfWeek);$i++){
            $saveArr = [
                'work_day' => $daysOfWeek[$i],
                'start_time' => '09:00:00',
                'end_time' => '06:00:00',
                'status' => 1,
            ];
            Schedule::create($saveArr);
        }
    }
}
