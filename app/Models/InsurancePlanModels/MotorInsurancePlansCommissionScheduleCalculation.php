<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\SoftDeletes;
class MotorInsurancePlansCommissionScheduleCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'motor_insurance_plans_commission_schedule_calculation';

    protected $fillable = [
        'motor_insurance_plan_id',
        'vehicle_type_id',
        'from',
        'to',
        'commission',
    ];

    /**
     * Optional relationship to VehicleCategory
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
