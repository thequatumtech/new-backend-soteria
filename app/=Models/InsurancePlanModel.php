<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsurancePlanModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'insurance_plans';
    protected $fillable = [
        'company_name',
        'lineofbussines',
        'plan_name',
        'limit',
        'plan_fee',
        'sales_tax',
        'net_premium',
        'gross_premium',
        'commission',
        'stamp_fee',
        'commission_percent',

    ];
}
