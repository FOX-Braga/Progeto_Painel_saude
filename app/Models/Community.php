<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'population_1_to_5',
        'population_5_to_10',
        'population_10_to_18',
    ];
}
