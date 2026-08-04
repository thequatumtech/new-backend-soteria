<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutomativeInsuranceModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'automative_insurance';
    protected $fillable = [
        'vahicle_type',
        'vahicle_brand',
        'vahicle_category',
        'vahicle_color',
        'vahicle_reg_no',
        'vahicle_photo',
        'insurance_type',
        'policy_holder',
        'national_id',
        'chassis_no',
        'no_of_prev_accident',
        'no_of_passanger',
        'engine_type',
        'engine_capacity',
        'moto_engine_no',
        'manufacture_date',
        'total_tickets',
        'insurance_amount',
    ];
    protected $dates = ['deleted_at'];
}
