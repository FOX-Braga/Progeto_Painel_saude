<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'child_id',
        'user_id',
        'record_date',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'record_date' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
