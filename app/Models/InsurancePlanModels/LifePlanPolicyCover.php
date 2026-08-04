<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LifePlanPolicyCover extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'life_plan_policy_covers';
    protected $fillable = [
        'life_plan_id',
        'cover_name',
        'cover_limit',
        'cover_limit_type',
    ];
}
