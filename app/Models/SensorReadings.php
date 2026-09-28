<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReadings extends Model
{
    protected $fillable = [
        'visitors',
        'water_level',
        'air_quality',
    ];
}
