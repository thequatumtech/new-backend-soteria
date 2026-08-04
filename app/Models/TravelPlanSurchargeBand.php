<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelPlanSurchargeBand extends Model
{
    use SoftDeletes;

    protected $table = 'travel_plan_surcharge_bands';

    protected $fillable = [
        'travel_plan_id',
        'min_age',
        'max_age',
        'surcharge',
    ];

    protected $casts = [
        'min_age'   => 'integer',
        'max_age'   => 'integer',
        'surcharge' => 'float',
    ];

    public function travelPlan()
    {
        return $this->belongsTo(
            \App\Models\InsurancePlanModels\TravelPlan::class,
            'travel_plan_id'
        );
    }
}
