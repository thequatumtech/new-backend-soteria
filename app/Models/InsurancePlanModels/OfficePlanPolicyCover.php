<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficePlanPolicyCover extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'office_plan_policy_covers';
    protected $fillable = [
        'office_plan_id',
        'cover_limit',
        'cover_name',
        'cover_limit_type',
        'cover_deductible',
        'cover_deductible_type',
        'cover_rate',   
        'cover_rate_type',
        'cover_premium',
        'created_by',
        'updated_by',
    ];
}
