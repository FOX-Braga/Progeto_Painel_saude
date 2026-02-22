<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MedicalRecord;
use App\Models\Community;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = MedicalRecord::with(['child.community', 'doctor'])->latest()->get();
        return view('medical_records.index', compact('records'));
    }

    public function create(Request $request)
    {
        $childId = $request->query('child_id');
        $child = null;

        if ($childId) {
            $child = Child::findOrFail($childId);
        }

        $communities = Community::orderBy('name')->get();
        return view('medical_records.form', compact('child', 'communities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'record_date' => 'required|date',
            // Validações se for criar nova criança
            'child_id' => 'nullable|exists:children,id',
            'child_name' => 'required_without:child_id',
            'community_id' => 'required_without:child_id|exists:communities,id',
            'birth_date' => 'required_without:child_id|date',
        ]);

        $childId = $request->child_id;

        // Se não tem ID de criança, vamos criar uma agora!
        if (!$childId) {
            $child = Child::create([
                'name' => $request->child_name,
                'community_id' => $request->community_id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'cns' => $request->cns,
                'guardian_name' => $request->guardian_name,
                'contact' => $request->contact,
                'address' => $request->address,
                'ethnicity' => $request->ethnicity,
            ]);
            $childId = $child->id;
        }

        $data = $request->except(['_token', 'child_id', 'record_date', 'child_name', 'community_id', 'birth_date', 'gender', 'cns', 'guardian_name', 'contact', 'address', 'ethnicity']);

        MedicalRecord::create([
            'child_id' => $childId,
            'user_id' => Auth::id(),
            'record_date' => $request->record_date,
            'data' => $data
        ]);

        return redirect()->route('children.show', $childId)->with('success', 'Paciente e Prontuário/Evolução registrados com sucesso!');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['child.community', 'doctor']);
        return view('medical_records.show', compact('medicalRecord'));
    }

    // API endpoint for dynamic dropdown
    public function getChildrenByCommunity($communityId)
    {
        $children = Child::where('community_id', $communityId)->orderBy('name')->get();
        return response()->json($children);
    }
}
