<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMedicalInsuranceMember extends Model
{
    use HasFactory;

    protected $table = 'family_medical_insurance_members';
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
        'marital_status',
        'occupancy_work',
        'height',
        'wight',
        'chronic_diseases_id',
        'previous_operation',
        'operation_details',
        'pregnant_status',
        'pregnant_month',
        'dangerous_status',
        'dangerous_id',
        'passport_front_id',
        'passport_back_id',
        'family_book_documents',
        'personal_picture_documents',
        'other_documents',
    ];
}
