<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientCompany extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'client_company_name',
        'client_company_registered_national_id_no',
        'client_company_registration_no',
        'client_company_country_id',
        'client_company_city_id',
        'client_company_district_id',
        'client_company_street_name',
        'client_company_building_no',
        'client_company_office_no',
        'client_company_telephone_no',
        'client_company_owner_first_name',
        'client_company_owner_father_name',
        'client_company_owner_grandfather_name',
        'client_company_owner_surname',
        'client_company_owner_telephone_no',
        'is_partner',
        'is_authorized',
        'authorized_position',
        'is_authorization_in_registration',
        'issuer_authorization_document',
        'ownership_document',
        'career_municipality_license',
        'company_tax_certificate',
        'practice_certificate',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'id','client_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'client_company_country_id');
    }

    public function city()
    {
        return $this->hasOne(Cities::class, 'id', 'client_company_city_id');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'client_company_district_id');
    }

}
