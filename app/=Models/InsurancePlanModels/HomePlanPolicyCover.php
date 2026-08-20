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

    public function home_plan()
    {
        return $this->belongsTo(HomePlan::class, 'home_plan_id', 'id');
    }
}
