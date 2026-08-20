<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubadminModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'subadmin';
    protected $fillable = [
        'full_name', 'mobile_no', 'admin_id', 'password', 'gender', 'national_id', 

    ];
    protected $dates = ['deleted_at'];
}
