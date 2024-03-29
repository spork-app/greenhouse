<?php

namespace Spork\Greenhouse\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spork\Core\Models\AbstractModel;

class Plant extends AbstractModel
{
    use LogsActivity;

    public $fillable = [
        'name',
        'time_to_harvest',
        'expected_yield_per_harvest',
        'is_perpetual_harvest',
        'nutrient_schedule',
        'settings',
    ];

    public $casts = [
        'settings' => 'array',
        'nutrient_schedule' => 'array',
    ];

    public function getValidationCreateRules(): array
    {
        return [
            'name' => 'string|required',
            'time_to_harvest' => 'integer',
            'expected_yield_per_harvest' => 'integer',
            'is_perpetual_harvest' => 'boolean',
            'nutrient_schedule' => 'array',
            'settings' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly([
            'name',
            'time_to_harvest',
            'expected_yield_per_harvest',
            'is_perpetual_harvest',
            'nutrient_schedule',
            'settings',
        ]);
        // Chain fluent methods for configuration options
    }
}
