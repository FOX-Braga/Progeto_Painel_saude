<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChildVaccine;
use Carbon\Carbon;

class ChildVaccineController extends Controller
{
    public function update(Request $request, ChildVaccine $childVaccine)
    {
        $validated = $request->validate([
            'applied_date' => 'required|date',
            'lot_number' => 'nullable|string|max:255',
            'professional' => 'nullable|string|max:255',
            'justification' => 'nullable|string',
            'status' => 'required|in:applied,suspended'
        ]);

        $childVaccine->update($validated);

        return redirect()->back()->with('success', 'Vacina atualizada com sucesso!');
    }
}
