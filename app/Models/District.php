<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class District extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['name','city_id'];
    public function city()
    {
        return $this->belongsTo(Cities::class,'city_id','id');
    }

}
