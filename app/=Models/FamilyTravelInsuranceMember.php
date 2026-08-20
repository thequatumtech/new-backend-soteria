<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyTravelInsuranceMember extends Model
{
    use HasFactory;

    protected $table = 'family_travel_insurance_members';
    protected $fillable = [
        'client_insurance_id',
        'first_name',
        'last_name',
        'third_name',
        'family_name',
        'relation',
        'nationality',
        'nationality_no',
        'id_residence_no',
        'birth_date',
        'gender',
        'place_residence',
        'passport_document',
    ];
}
