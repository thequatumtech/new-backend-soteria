<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceCompanyDocument extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'insurance_company_documents';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'insurance_id',
        'ownership_document',
        'municipality_license',
        'practice_certificate',
        'company_tax_certificate',
        'company_stamp',
        'authorized_signature',
        'letterhead',
        'logo'
    ];

    /**
     * Get the insurance company that owns the document.
     */
    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_id');
    }
}
