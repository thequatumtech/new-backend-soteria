<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Country;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(Cities::class, 'country_id','id');
    }
    public function currency()
    {
        return $this->hasOne(Currency::class, 'country_id','id');
    }
}
