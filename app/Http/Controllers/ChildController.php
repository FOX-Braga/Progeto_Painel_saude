<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Child;
use App\Models\Community;

class ChildController extends Controller
{
    public function index()
    {
        $communities = Community::with('children')->get();
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
        $child->load(['community', 'medical_records.doctor']); // eager load relation
        return view('children.show', compact('child'));
    }
}
