<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $communities = [
            [
                'name' => 'Aldeia Yawalapiti',
                'latitude' => -12.1500,
                'longitude' => -53.4000,
                'population_1_to_5' => 12,
                'population_5_to_10' => 18,
                'population_10_to_18' => 25,
            ],
            [
                'name' => 'Aldeia Kuikuro',
                'latitude' => -12.2333,
                'longitude' => -53.2167,
                'population_1_to_5' => 8,
                'population_5_to_10' => 14,
                'population_10_to_18' => 20,
            ],
            [
                'name' => 'Aldeia Kalapalo',
                'latitude' => -12.1833,
                'longitude' => -53.3000,
                'population_1_to_5' => 15,
                'population_5_to_10' => 22,
                'population_10_to_18' => 30,
            ],
            [
                'name' => 'Aldeia Mehinako',
                'latitude' => -12.3000,
                'longitude' => -53.4500,
                'population_1_to_5' => 10,
                'population_5_to_10' => 15,
                'population_10_to_18' => 22,
            ],
            [
                'name' => 'Aldeia Waurá',
                'latitude' => -12.2833,
                'longitude' => -53.5167,
                'population_1_to_5' => 7,
                'population_5_to_10' => 11,
                'population_10_to_18' => 18,
            ]
        ];

        foreach ($communities as $community) {
            Community::create($community);
        }
    }
}
