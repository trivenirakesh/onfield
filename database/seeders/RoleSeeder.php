<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveArr = [
            [
                'name' => 'Admin',
                'status' => 1,
            ],
            [
                'name' => 'User',
                'status' => 1,
            ]
        ];
        Role::insert($saveArr);
    }
}
