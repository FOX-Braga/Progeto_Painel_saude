<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Child;
use App\Models\MedicalRecord;
use App\Models\Community;
use App\Models\User;
use Carbon\Carbon;

class SimulatedCasesSeeder extends Seeder
{
    public function run(): void
    {
        // REGRA: NUNCA EXCLUIR REGISTROS, APENAS ADICIONAR OU EDITAR.

        $adminId = User::first()->id ?? 1;

        // Adicionar novas aldeias para teste
        $newCommunities = [
            'Aldeia Kamayurá',
            'Aldeia Aweti',
            'Aldeia Trumai',
            'Aldeia Nafuquá',
            'Aldeia Matipu',
            // MS / Pantanal
            'Aldeia Buriti',
            'Aldeia Cachoeirinha',
            'Aldeia Lalima',
            'Aldeia Limão Verde',
            'Aldeia Taunay/Ipegue',
            'Aldeia Guató',
            'Aldeia Kadiwéu',
            'Aldeia Bororó',
            'Aldeia Jaguapiru'
        ];

        foreach ($newCommunities as $nc) {
            $isMS = in_array($nc, ['Aldeia Buriti', 'Aldeia Cachoeirinha', 'Aldeia Lalima', 'Aldeia Limão Verde', 'Aldeia Taunay/Ipegue', 'Aldeia Guató', 'Aldeia Kadiwéu', 'Aldeia Bororó', 'Aldeia Jaguapiru']);

            $address = $isMS ? 'Mato Grosso do Sul / Pantanal, ' . $nc : 'Parque Indígena do Xingu, ' . $nc;
            $lat = $isMS ? -20.0 + (rand(-300, 300) / 100) : -12.0 + (rand(-100, 100) / 1000);
            $lng = $isMS ? -56.0 + (rand(-300, 300) / 100) : -53.0 + (rand(-100, 100) / 1000);

            Community::firstOrCreate(['name' => $nc], [
                'address' => $address,
                'population_1_to_5' => rand(15, 45),
                'population_5_to_10' => rand(20, 60),
                'population_10_to_18' => rand(30, 80),
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        }

        $communities = Community::all();

        if ($communities->isEmpty()) {
            $this->command->info('Nenhuma comunidade encontrada.');
            return;
        }

        $this->command->info('Gerando pacientes simulados variados para todas as aldeias...');

        $profiles = ['healthy', 'anemic', 'respiratory_asma', 'respiratory_pneumonia', 'malnourished', 'overweight', 'mental_health', 'delayed_vaccines'];

        foreach ($communities as $community) {
            foreach ($profiles as $profile) {

                // Determina características baseadas no perfil
                $isYoung = in_array($profile, ['anemic', 'respiratory_pneumonia', 'malnourished', 'delayed_vaccines']) ? true : (in_array($profile, ['mental_health']) ? false : (rand(0, 1) == 1));
                $ageYears = $isYoung ? rand(1, 6) : rand(10, 16);

                $nutritionalStatus = 'Adequado';
                if ($profile == 'malnourished')
                    $nutritionalStatus = 'Risco';
                if ($profile == 'overweight')
                    $nutritionalStatus = 'Atenção';
                if ($profile == 'anemic')
                    $nutritionalStatus = 'Atenção'; // Anemia frequently accompanied by attention

                $child = Child::create([
                    'name' => "S" . rand(10, 99) . " " . ucfirst($profile) . " " . explode(' ', $community->name)[1],
                    'birth_date' => Carbon::now()->subYears($ageYears)->subMonths(rand(1, 11))->format('Y-m-d'),
                    'gender' => (rand(0, 1) == 0) ? 'Feminino' : 'Masculino',
                    'community_id' => $community->id,
                    'nutritional_status' => $nutritionalStatus,
                    'cns' => '7' . rand(10000000000000, 99999999999999),
                    'guardian_name' => "Responsável " . rand(1, 100),
                    'ethnicity' => 'Indígena',
                    'contact' => '(99) 99999-9999',
                    'address' => "Setor " . rand(1, 5) . ", Casa " . rand(1, 40),
                ]);

                $baseDate = Carbon::now()->subYear()->subMonths(2);
                $baseWeight = $ageYears * 2 + 8;
                $baseHeight = $ageYears * 5 + 75;

                // Modificadores de peso base por perfil
                if ($profile == 'malnourished')
                    $baseWeight *= 0.70;
                if ($profile == 'overweight')
                    $baseWeight *= 1.40;

                for ($recordIndex = 1; $recordIndex <= 5; $recordIndex++) {

                    if ($recordIndex == 5) {
                        $recordDate = Carbon::today()->format('Y-m-d');
                    } else {
                        $baseDate->addMonths(3);
                        $recordDate = clone $baseDate;
                        $recordDate = $recordDate->format('Y-m-d');
                    }

                    $weight = $baseWeight + ($recordIndex * ($profile == 'malnourished' ? 0.2 : 0.6)) + (rand(-5, 5) / 10);
                    $height = $baseHeight + ($recordIndex * 1.5) + rand(0, 2);
                    $imc = $weight / (($height / 100) * ($height / 100));

                    $temp = 36.4 + (rand(0, 10) / 10);
                    $fc = rand(80, 100);
                    $spo2 = rand(96, 100);
                    $reason = 'Consulta de rotina';
                    $diagnosis = 'Z00.1 - Exame de rotina';
                    $prescription = 'Orientações gerais';

                    // Modificadores Clínicos Específicos por Perfil / Linha do Tempo
                    $hemoglobin = ($isYoung) ? 11.5 + (rand(-10, 10) / 10) : null;
                    $vaccineStatus = 'Completa';
                    $vaccineNotes = 'Calendário em dia';

                    if ($profile == 'anemic') {
                        // Começa com anemia severa e melhora, ou já crônica
                        $hemoglobin = 8.0 + ($recordIndex * 0.5) + (rand(-2, 2) / 10);
                        if ($hemoglobin < 11.0) {
                            $reason = 'Acompanhamento de Anemia Ferropriva';
                            $diagnosis = 'D50 - Anemia por deficiência de ferro';
                            $prescription = 'Suplementação de Sulfato Ferroso 3mg/kg/dia';
                        }
                    }

                    if ($profile == 'respiratory_asma') {
                        if ($recordIndex == 2 || $recordIndex == 4) {
                            $reason = 'Falta de ar, tosse seca';
                            $diagnosis = 'J45 - Asma não especificada';
                            $prescription = 'Salbutamol spray, corticóide inalatório';
                            $spo2 = rand(90, 94);
                            $fc = rand(110, 130);
                        }
                    }

                    if ($profile == 'respiratory_pneumonia' && $recordIndex == 3) {
                        $reason = 'Febre alta, tosse produtiva, apatia';
                        $diagnosis = 'J18.9 - Pneumonia não especificada';
                        $prescription = 'Amoxicilina + Clavulanato, reidratação, observação';
                        $temp = rand(385, 395) / 10;
                        $spo2 = rand(88, 92);
                        $fc = rand(120, 140);
                    }

                    if ($profile == 'delayed_vaccines') {
                        $vaccineStatus = 'Incompleta / Atrasada';
                        $vaccineNotes = 'Mãe relata dificuldade de acesso à UBS. Vacinas pendentes.';
                    }

                    if ($profile == 'mental_health' && !$isYoung) {
                        if ($recordIndex >= 3) {
                            $reason = 'Tristeza profunda, isolamento';
                            $diagnosis = 'F32 - Episódio depressivo';
                            $prescription = 'Acolhimento psicológico, encaminhamento DSEI Saúde Mental';
                        }
                    }

                    $data = [
                        'common' => [
                            'vitals' => [
                                'weight' => number_format($weight, 2, '.', ''),
                                'height' => number_format($height, 1, '.', ''),
                                'imc' => number_format($imc, 2, '.', ''),
                                'temperature' => number_format($temp, 1, '.', ''),
                                'heart_rate' => $fc,
                                'resp_rate' => rand(20, 30),
                                'spo2' => $spo2,
                            ],
                            'history' => [
                                'visit_reason' => $reason,
                            ],
                            'medical_action' => [
                                'diagnosis' => $diagnosis,
                                'prescription' => $prescription,
                            ]
                        ],
                    ];

                    if ($isYoung) {
                        $data['age_0_7'] = [
                            'growth_curve' => $profile == 'malnourished' ? 'Abaixo do P3' : 'Adequado',
                            'hemoglobin' => $hemoglobin ? number_format($hemoglobin, 1, '.', '') : '',
                            'vaccination_notes' => $vaccineNotes,
                            'vaccines' => [], // Empty array is fine, we check $vaccineNotes
                        ];
                        // Add some logic for vaccine status parsing
                        $data['pediatric']['vaccines']['status'] = $vaccineStatus;
                    } else {
                        $data['age_7_13'] = [];
                        if ($profile == 'mental_health') {
                            $data['age_7_13']['mental_health'] = [
                                'behavior' => 'Isolamento, recusa alimentar',
                                'anxiety' => 'Sinais de depressão/ansiedade moderada'
                            ];
                        }
                    }

                    MedicalRecord::create([
                        'child_id' => $child->id,
                        'user_id' => $adminId,
                        'record_date' => $recordDate,
                        'data' => $data
                    ]);
                }
            }
        }
        $this->command->info('Dados massivos inseridos com sucesso!');
    }
}
