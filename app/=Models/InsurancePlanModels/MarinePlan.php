<?php

namespace App\Models\InsurancePlanModels;

use App\Models\InsuranceCompany;
use App\Models\LineOfBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarinePlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function insurance_company()
    {
        return $this->belongsTo(InsuranceCompany::class,'insurance_company_id','id');
    }

    public function line_of_business()
    {
        return $this->belongsTo(LineOfBusiness::class,'line_of_business_id','id');
    }

    public function policy_covers()
    {
        return $this->hasMany(MarinePlanPolicyCover::class, 'marine_plan_id', 'id');
    }
    public static function getMarinePlanLimit($request)
    {
        $plan = MarinePlan::select('limit')->where('type_of_cover_id',$request->type_of_cover)->whereRaw("FIND_IN_SET(?, sub_category_allowed)", [$request->item_subcategory])->groupBy('limit')->get();
        return $plan;
    } 
    public static function getMarineInsurancePlanByLimit($limit)
    {
        $plan = MarinePlan::select('plan_name')->where('limit',$limit)->groupBy('plan_name')->get();
        return $plan;
    }
}
