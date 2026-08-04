<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorInsurancePlan3MonthsCompulsoryPremium extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'motor_insurance_plan_3_months_compulsory_premiums';
        protected $fillable = [
        'motor_insurance_plan_id',
        'vehicle_type',
        'premium',
        'created_by',
        'updated_by',
    ];
}
