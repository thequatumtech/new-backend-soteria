<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeInsurancePlan extends Model
{
    use HasFactory;

    protected $table = 'home_insurance_plans';
    protected $fillable = [
        'customer_id',
        'fname',
        'nationality',
        'national_id',
        'coverage',
        'category',
        'sizeofvilla',
        'location',
        'nooffloors',
        'noofrooms',
        'homecategory',
        'effectivedate',
        'expirydate',
        'limit',
        'BuildingNo',
        'BlockNo',
        'PlaateNo',
        'PlotNo',
        'NoResident',
    ];
}
