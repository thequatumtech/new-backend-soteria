<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermsAndCondition extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'terms_and_conditions';
    protected $dates = ['deleted_at'];
    protected $fillable = ['mobile', 'message'];
}
