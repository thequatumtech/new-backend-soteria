<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompany extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'insurance_companies';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'company_name',
        'line_of_business_id',
        'national_id',
        'register_number',
        'tax_number',
        'email',
        'email_1',
        'email_2',
        'claim_email',
        'mobile_number',
        'telephone_number',
        'joining_date',
        'currency_id',
        'country_id',
        'city_id',
        'district_id',
        'street_name',
        'building_no',
        'privacy_policy',
        'status'
    ];

    /**
     * Get the documents associated with the insurance company.
     */
    public function documents()
    {
        return $this->hasMany(InsuranceCompanyDocument::class, 'insurance_id');
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    
}
