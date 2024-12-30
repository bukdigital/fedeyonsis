<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgeCategory;

class AgeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'U14',
                'min_age' => 12,
                'max_age' => 14,
            ],
            [
                'name' => 'U16',
                'min_age' => 14,
                'max_age' => 16,
            ],
            [
                'name' => 'U18',
                'min_age' => 16,
                'max_age' => 18,
            ],
            [
                'name' => 'U21',
                'min_age' => 18,
                'max_age' => 21,
            ],
            [
                'name' => 'Büyükler',
                'min_age' => 21,
                'max_age' => 99,
            ],
        ];

        foreach ($categories as $category) {
            AgeCategory::create($category);
        }
    }
}
