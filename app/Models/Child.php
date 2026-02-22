<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = [
        'community_id',
        'name',
        'birth_date',
        'gender',
        'cns',
        'guardian_name',
        'contact',
        'address',
        'ethnicity',
        'last_visit_date',
        'nutritional_status',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function medical_records()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
