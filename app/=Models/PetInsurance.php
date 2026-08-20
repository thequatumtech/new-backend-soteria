<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetInsurance extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'pet_insurance';
    protected $fillable = [
        'pet_type',
        'owner_name',
        'pet_name',
        'pet_age',
        'gender',
        'color',
        'pri_conditions'
    ];
    protected $dates = ['deleted_at'];
}
