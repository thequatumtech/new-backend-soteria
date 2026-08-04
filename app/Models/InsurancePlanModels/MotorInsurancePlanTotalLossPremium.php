<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorInsurancePlanTotalLossPremium extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'motor_insurance_plan_total_loss_premiums';
    protected $fillable = [
        'motor_insurance_plan_id',
        'vehicle_brand_id',
        'vehicle_category_id',
        'insured_value',
        'premium',
        'created_by',
        'updated_by',
    ];
}
