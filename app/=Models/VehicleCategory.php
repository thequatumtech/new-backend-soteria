<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicle_categories';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'vehicle_brand_id',
        'status'
    ];

    public function vehicleBrand(){
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }
}
