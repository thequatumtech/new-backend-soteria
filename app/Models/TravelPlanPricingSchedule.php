<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelPlanPricingSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'travel_plan_pricing_schedules';

    protected $fillable = [
        'travel_plan_id',
        'min_days',
        'max_days',
        'price',
    ];

    protected $casts = [
        'min_days' => 'integer',
        'max_days' => 'integer',
        'price'    => 'float',
    ];

    public function travelPlan()
    {
        return $this->belongsTo(
            \App\Models\InsurancePlanModels\TravelPlan::class,
            'travel_plan_id'
        );
    }
}
