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

    public function child_vaccines()
    {
        return $this->hasMany(ChildVaccine::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($child) {
            // Se existem vacinas cadastradas no master catalog, auto-gera a carteirinha
            $vaccines = Vaccine::all();
            if ($vaccines->isNotEmpty() && $child->birth_date) {
                $birthDate = \Carbon\Carbon::parse($child->birth_date);
                foreach ($vaccines as $vaccine) {
                    $dueDate = $birthDate->copy()->addMonths($vaccine->months_due);
                    ChildVaccine::create([
                        'child_id' => $child->id,
                        'vaccine_id' => $vaccine->id,
                        'due_date' => $dueDate->format('Y-m-d'),
                        'status' => 'pending'
                    ]);
                }
            }
        });
    }
}
