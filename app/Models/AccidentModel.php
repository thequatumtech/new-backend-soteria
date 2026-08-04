<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccidentModel extends Model
{
    use HasFactory;

  
    protected $table = 'accident_insurance';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'age',
        'policy_holder_name',
        'national_id',
        'dob',
        'insurance_amount',
        'occupancy',
    ];
    
}
