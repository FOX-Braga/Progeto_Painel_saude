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
        // As linhas que apagavam o DB foram removidas permanentemente.

        $adminId = User::first()->id ?? 1;
        $communities = Community::all();

        if ($communities->isEmpty()) {
            $this->command->info('Nenhuma comunidade encontrada. Crie comunidades primeiro ou execute o CommunitySeeder.');
            return;
        }

        $this->command->info('Gerando pacientes simulados com históricos temporais completos...');

        foreach ($communities as $community) {
            // Cria 2 crianças por comunidade: 1 até 7 anos, 1 acima de 7 anos
            for ($i = 1; $i <= 2; $i++) {
                $isYoung = ($i % 2 !== 0);
                $ageYears = $isYoung ? rand(2, 6) : rand(8, 13);

                $child = Child::create([
                    'name' => "Criança Simulação {$community->name} " . rand(100, 999),
                    'birth_date' => Carbon::now()->subYears($ageYears)->subMonths(rand(1, 11))->format('Y-m-d'),
                    'gender' => ($i % 2 == 0) ? 'Feminino' : 'Masculino',
                    'community_id' => $community->id,
                    'nutritional_status' => 'Adequado',
                    'cns' => '7' . rand(10000000000000, 99999999999999),
                    'guardian_name' => "Responsável Aldeia",
                    'ethnicity' => 'Indígena',
                    'contact' => '(99) 99999-9999',
                    'address' => "{$community->name}, Casa " . rand(1, 50),
                ]);

                $baseDate = Carbon::now()->subYear()->subMonths(2); // Inicia as consultas há 14 meses
                $baseWeight = $ageYears * 2 + 8; // Aproximação de peso inicial
                $baseHeight = $ageYears * 5 + 75; // Aproximação de altura inicial

                // 5 consultas no total, espaçadas em alguns meses, sendo a última hoje
                for ($recordIndex = 1; $recordIndex <= 5; $recordIndex++) {

                    if ($recordIndex == 5) {
                        $recordDate = Carbon::today()->format('Y-m-d'); // Última consulta garantida como Hoje para aparecer na home
                    } else {
                        $baseDate->addMonths(3); // Cada consulta pula 3 meses
                        $recordDate = clone $baseDate;
                        $recordDate = $recordDate->format('Y-m-d');
                    }

                    // Simulando crescimento
                    $weight = $baseWeight + ($recordIndex * 0.6) + (rand(-5, 5) / 10);
                    $height = $baseHeight + ($recordIndex * 1.5) + rand(0, 2);
                    $imc = $weight / (($height / 100) * ($height / 100));

                    $temp = 36.4 + (rand(0, 10) / 10);
                    $fc = rand(85, 110);
                    $spo2 = rand(96, 100);
                    $hemoglobin = ($isYoung) ? 10.5 + ($recordIndex * 0.3) + (rand(-5, 5) / 10) : null; // Hemoglobina melhorando

                    $data = [
                        'common' => [
                            'vitals' => [
                                'weight' => number_format($weight, 2, '.', ''),
                                'height' => number_format($height, 1, '.', ''),
                                'imc' => number_format($imc, 2, '.', ''),
                                'temperature' => number_format($temp, 1, '.', ''),
                                'heart_rate' => $fc,
                                'resp_rate' => rand(20, 28),
                                'spo2' => $spo2,
                                'blood_pressure' => (!$isYoung) ? rand(95, 110) . '/' . rand(60, 75) : '',
                                'blood_sugar' => rand(85, 95),
                            ],
                            'history' => [
                                'visit_reason' => $recordIndex == 1 ? 'Primeira consulta de acompanhamento' : 'Consulta de rotina (' . $recordIndex . '/5)',
                                'current_disease_history' => 'Criança assintomática no momento da anamnese',
                                'pre_existing' => 'Nenhuma doença grave relatada',
                                'allergies' => 'Sem alergias declaradas',
                                'continuous_meds' => 'Nenhum'
                            ],
                            'evaluation' => [
                                'physical' => 'Bom estado geral, ativa, mucosas coradas e hidratadas, acianótica. Ausculta cardíaca e respiratória sem alterações.'
                            ],
                            'medical_action' => [
                                'diagnosis' => 'Z00.1 - Exame de rotina de saúde da criança',
                                'prescription' => 'Orientações sobre puericultura, nutrição e higiene. Atualizar caderneta de vacinas se necessário.',
                                'referrals' => 'Retorno agendado para o próximo trimestre.'
                            ]
                        ],
                        'indigenous' => [
                            'context' => [
                                'water_access' => 'Acesso por poço artesiano',
                                'sanitation' => 'Fossa rudimentar',
                                'electricity' => 'Energia solar ou gerador',
                                'house_type' => 'Estrutura de madeira e palha'
                            ],
                            'dsei' => [
                                'assistance' => 'Visita mensal da equipe multidisciplinar',
                                'last_visit' => rand(1, 3) . ' semanas atrás'
                            ],
                            'traditional' => [
                                'paje_visited' => ($recordIndex % 2 == 0) ? 'Sim' : 'Não',
                                'traditional_meds' => ($recordIndex % 2 == 0) ? 'Chás de ervas locais prescritos pelo Pajé' : 'Nenhum no momento'
                            ]
                        ]
                    ];

                    if ($isYoung) {
                        $data['age_0_7'] = [
                            'head_circumference' => ($ageYears <= 2) ? rand(46, 51) : '',
                            'growth_curve' => 'P50 (Adequado)',
                            'malnutrition_index' => 'Risco Baixo',
                            'exclusive_breastfeeding' => ($ageYears <= 1) ? '1' : '0',
                            'food_introduction' => '1',
                            'hemoglobin' => $hemoglobin ? number_format($hemoglobin, 1, '.', '') : '',
                            'vit_a_deficiency' => 'no',
                            'vaccines' => [
                                'bcg' => '1',
                                'pentavalente' => '1',
                                'poliomielite' => '1',
                                'triplice_viral' => '1',
                                'rotavirus' => '1',
                                'influenza' => '1',
                            ],
                            'vaccination_notes' => 'Calendário Vacinal em dia conforme o PNI para a idade.',
                            'development' => [
                                'milestones' => 'Atingiu os marcos esperados (sustenta a cabeça, senta, anda)',
                                'language' => 'Desenvolvimento de fala dentro do esperado, palavras simples e formação de frases',
                                'coordination' => 'Motricidade fina e grossa preservadas e estimuladas',
                                'school' => ($ageYears >= 4) ? 'yes' : 'no',
                            ],
                            'dental_health' => 'Dentição decídua sem lesões cariosas ativas, orientação de escovação realizada.',
                            'literacy' => ($ageYears >= 6) ? 'Reconhece letras e números' : '',
                            'risk' => [
                                'low_birth_weight' => '0',
                                'prematurity' => '0',
                                'recent_infections' => 'Nenhuma internação hospitalar por quadro agudo no último trimestre.'
                            ]
                        ];
                    } else {
                        $data['age_7_13'] = [
                            'education' => [
                                'attendance' => 'Frequenta escola local da aldeia',
                                'lag' => 'Sem defasagem',
                                'performance' => 'Bom acompanhamento escolar e socialização escolar'
                            ],
                            'food_security' => 'Adequada',
                            'anemia_status' => 'Avaliação clínica sem sinais de anemia',
                            'mental_health' => [
                                'behavior' => 'Comportamento sociável, obedece comandos, integridade mental',
                                'bullying' => 'Não sofre e não pratica na comunidade',
                                'anxiety' => 'Ausência de sinais de ansiedade ou depressão'
                            ],
                            'social_activities' => 'Participa dos rituais, práticas e jogos da comunidade',
                        ];
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
        $this->command->info('Dados inseridos com sucesso sem remover registros antigos!');
    }
}
