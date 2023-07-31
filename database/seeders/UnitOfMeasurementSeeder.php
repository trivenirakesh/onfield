<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitOfMeasurement::create([
            'name' => 'Liter',
            'description' => 'Liter',
            'factor' => 1,
            'status' => 1
        ]);
    }
}
