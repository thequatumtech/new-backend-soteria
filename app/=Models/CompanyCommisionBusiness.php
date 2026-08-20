<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyCommisionBusiness extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'company_commision_businesses';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'insurance_company_id',
        'line_of_business_id',
        'commision'
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
