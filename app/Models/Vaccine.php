<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $fillable = ['name', 'dose', 'months_due'];

    public function children()
    {
        return $this->hasMany(ChildVaccine::class);
    }
}
