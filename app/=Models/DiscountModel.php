<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'discount';
    protected $fillable = [
       'coupan_code', 'discount', 'insurance_type', 'discount_percentage', 'discount_description',
    ];
    protected $dates = ['deleted_at'];
}
