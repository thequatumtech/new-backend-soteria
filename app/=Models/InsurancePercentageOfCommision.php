<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsurancePercentageOfCommision extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'insurance_percentage_of_commisions';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'insurance_company_id',
        'line_of_business_id',
        'insurance_fee',
        'stamp',
        'tax',
    ];

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    public function lineOfBusiness()
    {
        return $this->belongsTo(LineOfBusiness::class, 'line_of_business_id');
    }
}
