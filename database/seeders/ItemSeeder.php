<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'Laptop',
            'description' => 'Laptop',
            'uom_id' => 1,
            'item_category_id' => 1,
            'price' => 100,
            'status' => 1
        ]);
    }
}
