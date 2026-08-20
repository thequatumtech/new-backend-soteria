<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentModel extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = 'agent';
    protected $fillable = [
        'first_name', 'father_name', 'grandfather_name', 'surname',
        'agent_code', 'created_at', 'updated_at', 'supervisor_name',
        'nationality_id', 'residence_no', 'birth_date', 'marital_status',
        'country_id', 'city_id', 'district_id', 'street_name',
        'building_no', 'id_front', 'id_back', 'profile_pic',
        'agent_email', 'agent_mobile_no', 'gender', 'joining_date',
        'nationalidpassport', 'agentcommision_no_general', 'agentcommision_no_life',
        'supervisor_id'
    ];
    protected $dates = ['deleted_at'];
    protected $appends = ['full_name'];

    public function getFullNameAttribute() // notice that the attribute name is in CamelCase.
    {
        return $this->first_name . ' ' . $this->father_name . ' ' . $this->grandfather_name. ' ' . $this->surname;
    }

    public function clients()
    {
        return $this->hasMany(Client::class,'agent_id', 'agent_code');
    }

    public function agent_commission()
    {
        return $this->hasMany(AgentCommission::class,'agent_id', 'id');
    }

    public function agent_supervisor_commission()
    {
        return $this->hasMany(AgentSupervisorCommission::class,'agent_id', 'id');
    }

    public function nationality()
    {
        return $this->hasOne(Nationality::class,'id','nationality_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class,'id','country_id');
    }

    public function city()
    {
        return $this->hasOne(Cities::class,'id','city_id');
    }

    public function district()
    {
        return $this->hasOne(District::class,'id','district_id');
    }

    public function supervisor()
    {
        return $this->hasOne(AgentModel::class,'id','supervisor_id');
    }
    public static function getSupervisor()
    {
        $supervisors = AgentModel::join('agent as supervisor', 'agent.supervisor_id', '=', 'supervisor.id')
            ->select('agent.supervisor_id as id', 'supervisor.first_name as first_name')
            ->distinct()
            ->get();
        return $supervisors;
    }
}
