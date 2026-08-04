<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cities extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['name','country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id','id');
    }

}
