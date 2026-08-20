<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorInsurancePlan9MonthsCompulsoryPremium extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'motor_insurance_plan_9_months_compulsory_premium';

}
