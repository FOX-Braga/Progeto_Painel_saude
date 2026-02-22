<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Child;
use App\Models\Community;
use Faker\Factory as Faker;

class ChildSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        $communities = Community::all();

        if ($communities->isEmpty()) {
            return;
        }

        $ethnicities = ['Guajajara', 'Kuikuro', 'Yawalapiti', 'Kalapalo', 'Waurá'];

        foreach ($communities as $community) {
            // Criar de 3 a 8 crianças por comunidade
            $numChildren = rand(3, 8);

            for ($i = 0; $i < $numChildren; $i++) {
                $gender = $faker->randomElement(['Masculino', 'Feminino']);

                Child::create([
                    'community_id' => $community->id,
                    'name' => $faker->firstName($gender == 'Masculino' ? 'male' : 'female') . ' ' . $faker->lastName . ' ' . $faker->lastName,
                    'birth_date' => $faker->dateTimeBetween('-15 years', '-1 months')->format('Y-m-d'),
                    'gender' => $gender,
                    'cns' => $faker->numerify('###.####.####.####'),
                    'guardian_name' => $faker->name,
                    'contact' => $faker->cellphoneNumber,
                    'address' => 'Residência ' . $faker->numberBetween(1, 50) . ', ' . $community->name,
                    'ethnicity' => $faker->randomElement($ethnicities),
                ]);
            }
        }
    }
}
