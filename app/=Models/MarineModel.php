<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarineModel extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    protected $table = 'marine_insurance';
    protected $fillable = [
        'mobile_no',
        'transportation',
        'policy_holder_name',
        'national_id',
        'from',
        'via',
        'to',
        'insurance_amount',
        'subject_matter_insurance',
    ];
    protected $dates = ['deleted_at'];
}
