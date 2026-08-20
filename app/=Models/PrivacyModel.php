<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivacyModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'privacy';
    protected $fillable = [
        'company_name', 'line_of_bussiness', 'privacy_policy_content', 'policy_file',

    ];
    protected $dates = ['deleted_at'];
}
