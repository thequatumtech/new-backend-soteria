<?php

namespace App\Models\InsurancePlanModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePlanPolicyCover extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'home_plan_policy_covers'; // Make sure this matches your table name
    protected $fillable = [
        'home_plan_id',
        'cover_name',
        'cover_limit',
        'cover_deductible',
        'conver_deductible_type',
        'cover_rate',   
        'cover_rate_type',
        'cover_premium',       
        'created_by',
        'updated_by',
    ];
    public function home_plan()
    {
        return $this->belongsTo(HomePlan::class, 'home_plan_id', 'id');
    }
}
