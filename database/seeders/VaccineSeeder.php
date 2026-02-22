<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;

class VaccineSeeder extends Seeder
{
    public function run(): void
    {
        $vaccines = [
            // Ao Nascer (0 meses)
            ['name' => 'BCG', 'dose' => 'Dose Única', 'months_due' => 0],
            ['name' => 'Hepatite B', 'dose' => 'Dose Única', 'months_due' => 0],

            // 2 Meses
            ['name' => 'Pentavalente (DTP/HB/Hib)', 'dose' => '1ª Dose', 'months_due' => 2],
            ['name' => 'Poliomielite (VIP)', 'dose' => '1ª Dose', 'months_due' => 2],
            ['name' => 'Pneumocócica 10 Valente', 'dose' => '1ª Dose', 'months_due' => 2],
            ['name' => 'Rotavírus Humano', 'dose' => '1ª Dose', 'months_due' => 2],

            // 3 Meses
            ['name' => 'Meningocócica C', 'dose' => '1ª Dose', 'months_due' => 3],

            // 4 Meses
            ['name' => 'Pentavalente (DTP/HB/Hib)', 'dose' => '2ª Dose', 'months_due' => 4],
            ['name' => 'Poliomielite (VIP)', 'dose' => '2ª Dose', 'months_due' => 4],
            ['name' => 'Pneumocócica 10 Valente', 'dose' => '2ª Dose', 'months_due' => 4],
            ['name' => 'Rotavírus Humano', 'dose' => '2ª Dose', 'months_due' => 4],

            // 5 Meses
            ['name' => 'Meningocócica C', 'dose' => '2ª Dose', 'months_due' => 5],

            // 6 Meses
            ['name' => 'Pentavalente (DTP/HB/Hib)', 'dose' => '3ª Dose', 'months_due' => 6],
            ['name' => 'Poliomielite (VIP)', 'dose' => '3ª Dose', 'months_due' => 6],
            ['name' => 'Covid-19', 'dose' => '1ª Dose', 'months_due' => 6],

            // 9 Meses
            ['name' => 'Febre Amarela', 'dose' => 'Dose Inicial', 'months_due' => 9],

            // 12 Meses (1 Ano)
            ['name' => 'Tríplice Viral (SCR)', 'dose' => '1ª Dose', 'months_due' => 12],
            ['name' => 'Pneumocócica 10 Valente', 'dose' => 'Reforço', 'months_due' => 12],
            ['name' => 'Meningocócica C', 'dose' => 'Reforço', 'months_due' => 12],

            // 15 Meses
            ['name' => 'DTP (Tríplice Bacteriana)', 'dose' => '1º Reforço', 'months_due' => 15],
            ['name' => 'Poliomielite (VOP)', 'dose' => '1º Reforço', 'months_due' => 15],
            ['name' => 'Hepatite A', 'dose' => 'Dose Única', 'months_due' => 15],
            ['name' => 'Tetraviral (SCRV)', 'dose' => 'Dose Única', 'months_due' => 15],

            // 4 Anos
            ['name' => 'DTP (Tríplice Bacteriana)', 'dose' => '2º Reforço', 'months_due' => 48],
            ['name' => 'Poliomielite (VOP)', 'dose' => '2º Reforço', 'months_due' => 48],
            ['name' => 'Febre Amarela', 'dose' => 'Reforço', 'months_due' => 48],

            // 9 a 14 Anos
            ['name' => 'HPV', 'dose' => 'Dose Única', 'months_due' => 114], // approx 9.5 years
            ['name' => 'Meningocócica ACWY', 'dose' => 'Dose Única', 'months_due' => 132], // 11 years
        ];

        foreach ($vaccines as $vac) {
            Vaccine::firstOrCreate(['name' => $vac['name'], 'dose' => $vac['dose']], $vac);
        }
    }
}
