<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualPlanModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'individual_plan';
    protected $fillable = [
        'user_id', 'individual_type', 'age', 'family_data', 'policy_holder', 'national_id', 'id_no',
        'previouse_medical_case', 'medical_details'
    ];
    protected $dates = ['deleted_at'];
}
