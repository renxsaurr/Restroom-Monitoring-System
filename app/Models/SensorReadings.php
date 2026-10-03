<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReadings extends Model
{
    protected $fillable = [
        'people_count',
        'cubicle_1_occupied',
        'cubicle_2_occupied',
        'tcs_red',
        'tcs_green',
        'tcs_blue',
        'tcs_clear',
        'water_state',
        'mq135_raw',
        'mq135_state',
    ];

    protected function casts(): array
    {
        return [
            'people_count' => 'integer',
            'cubicle_1_occupied' => 'boolean',
            'cubicle_2_occupied' => 'boolean',
            'tcs_red' => 'integer',
            'tcs_green' => 'integer',
            'tcs_blue' => 'integer',
            'tcs_clear' => 'integer',
            'mq135_raw' => 'integer',
        ];
    }
}
