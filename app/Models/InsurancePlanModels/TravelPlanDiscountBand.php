<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TravelPlanDiscountBand extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'travel_plan_id',
        'min_age',
        'max_age',
        'discount',
    ];
}
