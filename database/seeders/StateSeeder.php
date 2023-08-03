<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveArr = [
            'Andhra Pradesh', 'Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat',
            'Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala',
            'Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha',
            'Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh',
            'Uttarakhand','West Bengal',
        ];
        foreach($saveArr as $key => $value){
            State::create([
                'name' => $value
            ]);
        }
    }
}
