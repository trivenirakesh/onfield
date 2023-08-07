<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saveArr = [
            [
                'slug' => 'about-us',
                'title' => 'About-Us',
                'content' => 'About-Us',
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy-Policy',
                'content' => 'Privacy-Policy',
            ],
            [
                'slug' => 'terms-condition',
                'title' => 'Terms & Condition',
                'content' => 'Terms & Condition',
            ]
        ];
        StaticPage::insert($saveArr);
    }
}
