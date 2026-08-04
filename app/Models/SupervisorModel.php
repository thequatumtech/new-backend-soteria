<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupervisorModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'supervisor';
    protected $fillable = [
        'svname',
        'svemail',
        'svmobile_no',
        'sv_gender',
        'joining_date',
        'nationalandpassportno',
        'agent_commission',
        'override_commission',
       
    ];
    protected $dates = ['deleted_at'];
}
