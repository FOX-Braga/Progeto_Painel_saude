<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildVaccine extends Model
{
    protected $fillable = [
        'child_id',
        'vaccine_id',
        'due_date',
        'applied_date',
        'status',
        'lot_number',
        'professional',
        'justification'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
}
