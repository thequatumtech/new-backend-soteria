<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CriticalIllnessPlanPolicyCover extends Model
{
    use HasFactory;
    use SoftDeletes;

     protected $fillable = [
        'critical_illness_plan_id',
        'cover_name',
        'cover_limit',
        'cover_limit_type',
        'cover_deductible',
        'cover_deductible_type',
        'cover_premium',
    ];
}
