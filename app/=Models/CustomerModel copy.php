<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CustomerModel extends Authenticatable implements HasMedia
{
    use HasFactory, HasApiTokens;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'customers';
    protected $fillable = [
        'full_name', 'email', 'occupation', 'mobile_no', 'gender',
        'address', 'dob', 'housenoandbuildingname', 'street', 'country',
        'city', 'state', 'district'
    ];
    protected $dates = ['deleted_at'];
    protected $appends = ['customer_file'];

    function getCustomerFileAttribute() {
        $mediaItem = $this->getMedia('customer-files')->first();
        $array = [];
        if($mediaItem) {
            $array = [
                "src" => $mediaItem->getFullUrl(),
                "name" => $mediaItem->file_name,
                "delete_url" => route('deleteMedia', ["id" => $mediaItem->id]),
                "download_url" => route('downloadMedia', ["id" => $mediaItem->id])
            ];
            return $array;
        }
        return null;
    }
}
