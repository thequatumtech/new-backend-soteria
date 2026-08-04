<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name', 'father_name', 'grandfather_name', 'surname',
        'language', 'nationality_id', 'national_id_number', 'residence_id_number',
        'birth_date', 'gender', 'marital_status', 'employment_type', 'email_id', 'mobile_no', 'country_id',
        'residing_country_same', 'residing_country_id', 'city_id', 'district_id',
        'street_name', 'building_no', 'company_name', 'occupation_id', 'position', 'work_nature',
        'company_city_id', 'company_district_id', 'company_street_name', 'company_building_no',
        'company_contact_no', 'id_front', 'id_back', 'profile_pic', 'agent_id',
        'password', 'no_of_policies','fcm_token'
    ];

    protected $appends = ['full_name'];
    protected $hidden = ['password'];

    public function getFullNameAttribute() // notice that the attribute name is in CamelCase.
    {
        return $this->first_name . ' ' . $this->father_name . ' ' . $this->grandfather_name. ' ' . $this->surname;
    }

    public function agent()
    {
        return $this->hasOne(AgentModel::class, 'agent_code', 'agent_id');
    }

    public function nationality()
    {
        return $this->hasOne(Nationality::class, 'id', 'nationality_id');
    }

    public function city()
    {
        return $this->hasOne(Cities::class, 'id', 'city_id');
    }

    public function company_city()
    {
        return $this->hasOne(Cities::class, 'id', 'company_city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function residing_country()
    {
        return $this->hasOne(Country::class, 'id', 'residing_country_id');
    }

    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    public function company_district()
    {
        return $this->hasOne(District::class, 'id', 'company_district_id');
    }

    public function occupation()
    {
        return $this->hasOne(Occupations::class, 'id', 'occupation_id');
    }

    public function company()
    {
        return $this->hasOne(ClientCompany::class, 'client_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(ClientMessage::class, 'client_id', 'id');
    }

    public function black_list_detail()
    {
        return $this->hasOne(BlackListDetail::class, 'client_id', 'id');
    }

    public function purchased_policies()
    {
        return $this->hasMany(PurchasePolicy::class, 'client_id', 'id');
    }

}
