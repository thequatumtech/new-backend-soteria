<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorInsurancePlanComprehensiveCoverPremium extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'motor_insurance_plan_comprehensive_cover_premiums';
    protected $fillable = [
        'motor_insurance_plan_id',
        'vehicle_brand_id',
        'vehicle_category_id',
        'from',
        'to',
        'premium',
        'premium_type',
        'created_by',
        'updated_by',
    ];
    public function vehicle_brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    public function vehicle_category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id');
    }
}
