<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleColor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle_colors';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'status'
    ];
}
