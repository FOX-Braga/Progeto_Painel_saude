<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Child;
use App\Models\Community;

class ChildController extends Controller
{
    public function index()
    {
        $communities = Community::with(['children.medical_records'])->get();
        return view('children.index', compact('communities'));
    }

    public function create()
    {
        $communities = Community::orderBy('name')->get();
        return view('children.create', compact('communities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'community_id' => 'required|exists:communities,id',
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $child = Child::create($request->all());

        return redirect()->route('children.show', $child->id)->with('success', 'Paciente cadastrado! Prontuário aberto.');
    }

    public function show(Child $child)
    {
        $child->load(['community', 'medical_records.doctor', 'child_vaccines.vaccine']); // eager load relation

        // Prepare data for the health charts
        $chartDates = [];
        $chartWeights = [];
        $chartHeights = [];
        $chartIMCs = [];
        $chartFcs = [];
        $chartTemps = [];
        $chartHemoglobins = [];

        $recordsAsc = $child->medical_records->sortBy('record_date');
        foreach ($recordsAsc as $record) {
            $vitals = $record->data['common']['vitals'] ?? [];
            $age07 = $record->data['age_0_7'] ?? [];
            $hasData = (!empty($vitals['weight']) || !empty($vitals['height']) || !empty($vitals['imc']) || !empty($vitals['heart_rate']) || !empty($vitals['temperature']) || !empty($age07['hemoglobin']));

            if ($hasData) {
                $chartDates[] = \Carbon\Carbon::parse($record->record_date)->format('d/m');
                // Replace comma with dot if necessary and parse to float
                $chartWeights[] = !empty($vitals['weight']) ? (float) str_replace(',', '.', $vitals['weight']) : null;
                $chartHeights[] = !empty($vitals['height']) ? (float) str_replace(',', '.', $vitals['height']) : null;
                $chartIMCs[] = !empty($vitals['imc']) ? (float) str_replace(',', '.', $vitals['imc']) : null;
                $chartFcs[] = !empty($vitals['heart_rate']) ? (float) str_replace(',', '.', $vitals['heart_rate']) : null;
                $chartTemps[] = !empty($vitals['temperature']) ? (float) str_replace(',', '.', $vitals['temperature']) : null;
                $chartHemoglobins[] = !empty($age07['hemoglobin']) ? (float) str_replace(',', '.', $age07['hemoglobin']) : null;
            }
        }

        $chartData = [
            'labels' => $chartDates,
            'weights' => $chartWeights,
            'heights' => $chartHeights,
            'imcs' => $chartIMCs,
            'fcs' => $chartFcs,
            'temps' => $chartTemps,
            'hemoglobins' => $chartHemoglobins,
        ];

        return view('children.show', compact('child', 'chartData'));
    }
}
