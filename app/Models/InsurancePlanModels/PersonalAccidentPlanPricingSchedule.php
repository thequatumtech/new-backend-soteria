<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalAccidentPlanPricingSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'personal_accident_plan_pricing_schedules'; // Make sure this matches your table name
    protected $fillable = [
        'personal_accident_plan_id',
        'age',
        'm_1',
        'm_2',
        'm_3',
        'm_4',
        'm_5',
        'm_6',
        'm_7',
        'm_8',
        'm_9',
        'm_10',
        'm_11',
        'm_12',
    ];
}
