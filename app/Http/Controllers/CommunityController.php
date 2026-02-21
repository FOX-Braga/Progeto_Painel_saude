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
}
