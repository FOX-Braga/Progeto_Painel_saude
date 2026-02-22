<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Community::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', '%' . $search . '%')
                ->orWhere('address', 'ilike', '%' . $search . '%');
        }

        $communities = $query->get();

        // Calcular totais
        $totalHabitantes = $communities->sum(function ($c) {
            return $c->population_1_to_5 + $c->population_5_to_10 + $c->population_10_to_18;
        });

        $total1a5 = $communities->sum('population_1_to_5');
        $total5a10 = $communities->sum('population_5_to_10');
        $total10a18 = $communities->sum('population_10_to_18');

        return view('communities.index', compact('communities', 'totalHabitantes', 'total1a5', 'total5a10', 'total10a18'));
    }

    public function create()
    {
        return view('communities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'population_1_to_5' => 'required|integer|min:0',
            'population_5_to_10' => 'required|integer|min:0',
            'population_10_to_18' => 'required|integer|min:0',
        ]);

        if (empty($validated['latitude']) || empty($validated['longitude'])) {
            if (!empty($validated['address'])) {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'CuruminCRM/1.0'
                ])->get('https://nominatim.openstreetmap.org/search', [
                            'q' => $validated['address'],
                            'format' => 'json',
                            'limit' => 1
                        ]);

                if ($response->successful() && count($response->json()) > 0) {
                    $location = $response->json()[0];
                    $validated['latitude'] = $location['lat'];
                    $validated['longitude'] = $location['lon'];
                } else {
                    return back()->withInput()->withErrors(['address' => 'Não foi possível encontrar as coordenadas (latitude/longitude) para o endereço informado. Insira manualmente ou refine o endereço.']);
                }
            } else {
                return back()->withInput()->withErrors(['latitude' => 'Latitude e Longitude são obrigatórios caso não seja fornecido um endereço georeferenciável.']);
            }
        }

        Community::create($validated);

        return redirect()->route('communities.index')->with('success', 'Comunidade cadastrada com sucesso!');
    }

    public function edit(Community $community)
    {
        return view('communities.form', compact('community'));
    }

    public function update(Request $request, Community $community)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'population_1_to_5' => 'required|integer|min:0',
            'population_5_to_10' => 'required|integer|min:0',
            'population_10_to_18' => 'required|integer|min:0',
        ]);

        // ... validation logic omitted ...
        if (empty($validated['latitude']) || empty($validated['longitude'])) {
            if (!empty($validated['address'])) {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'User-Agent' => 'CuruminCRM/1.0'
                ])->get('https://nominatim.openstreetmap.org/search', [
                            'q' => $validated['address'],
                            'format' => 'json',
                            'limit' => 1
                        ]);

                if ($response->successful() && count($response->json()) > 0) {
                    $location = $response->json()[0];
                    $validated['latitude'] = $location['lat'];
                    $validated['longitude'] = $location['lon'];
                } else {
                    return back()->withInput()->withErrors(['address' => 'Não foi possível encontrar as coordenadas (latitude/longitude) para o endereço informado. Insira manualmente ou refine o endereço.']);
                }
            } else {
                return back()->withInput()->withErrors(['latitude' => 'Latitude e Longitude são obrigatórios caso não seja fornecido um endereço georeferenciável.']);
            }
        }

        $community->update($validated);

        return redirect()->route('communities.index')->with('success', 'Comunidade atualizada com sucesso!');
    }

    public function show(Community $community)
    {
        $community->load(['children.medical_records']);

        // Data Aggregation
        $stats = [
            'nutrition' => [
                'Adequado' => 0,
                'Atenção' => 0,
                'Risco' => 0
            ],
            'anemia' => [
                'Normal' => 0,
                'Baixa (Anemia)' => 0
            ],
            'vaccines' => [
                'Completa' => 0,
                'Pendente/Atrasada' => 0
            ],
            'respiratory' => [],
            'ages' => [],
        ];

        foreach ($community->children as $child) {
            // Idade
            if ($child->birth_date) {
                $age = \Carbon\Carbon::parse($child->birth_date)->age;
                if (!isset($stats['ages'][$age])) {
                    $stats['ages'][$age] = 0;
                }
                $stats['ages'][$age]++;
            }

            // Nutrição
            $statusNutri = ucfirst(strtolower($child->nutritional_status ?? 'Adequado'));
            if ($statusNutri == 'Atencao' || $statusNutri == 'Atenção')
                $statusNutri = 'Atenção';

            if (isset($stats['nutrition'][$statusNutri])) {
                $stats['nutrition'][$statusNutri]++;
            }

            // Pegar último prontuário para Anemia e Vacinas
            $latestRecord = $child->medical_records->sortByDesc('record_date')->first();
            if ($latestRecord) {
                $data = $latestRecord->data ?? [];

                // Anemia
                $hemo = $data['age_0_7']['hemoglobin'] ?? null;
                if ($hemo) {
                    $hemoVal = (float) str_replace(',', '.', $hemo);
                    if ($hemoVal < 11.0) {
                        $stats['anemia']['Baixa (Anemia)']++;
                    } else {
                        $stats['anemia']['Normal']++;
                    }
                }

                // Vacinas
                $vaxNotes = $data['age_0_7']['vaccination_notes'] ?? '';
                $statusVax = $data['pediatric']['vaccines']['status'] ?? '';

                if (stripos($vaxNotes, 'pendente') !== false || stripos($vaxNotes, 'atraso') !== false || stripos($statusVax, 'pendente') !== false || stripos($statusVax, 'incompleto') !== false) {
                    $stats['vaccines']['Pendente/Atrasada']++;
                } else {
                    // Assume completa if not explicitly pending
                    $stats['vaccines']['Completa']++;
                }
            }

            // Histórico Respiratório (todos os prontuários)
            foreach ($child->medical_records as $rec) {
                $reason = strtolower($rec->data['common']['history']['visit_reason'] ?? '');
                $diag = strtolower($rec->data['common']['medical_action']['diagnosis'] ?? '');

                // Simple keyword matching for respiratory issues in the seeded data
                if (str_contains($reason, 'respirat') || str_contains($diag, 'pneumonia') || str_contains($diag, 'asma') || str_contains($reason, 'tosse') || str_contains($reason, 'febre')) {
                    $month = \Carbon\Carbon::parse($rec->record_date)->format('m/Y');
                    if (!isset($stats['respiratory'][$month])) {
                        $stats['respiratory'][$month] = 0;
                    }
                    $stats['respiratory'][$month]++;
                }
            }
        }

        // Sort respiratory data chronologically
        uksort($stats['respiratory'], function ($a, $b) {
            $dateA = \Carbon\Carbon::createFromFormat('m/Y', $a);
            $dateB = \Carbon\Carbon::createFromFormat('m/Y', $b);
            return $dateA->greaterThan($dateB) ? 1 : -1;
        });

        ksort($stats['ages']);

        return view('communities.show', compact('community', 'stats'));
    }
}
