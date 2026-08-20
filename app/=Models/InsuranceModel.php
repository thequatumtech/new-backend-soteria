<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class InsuranceModel extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $table = 'insurance';
    protected $fillable = [
        'full_name',
        'license_number',
        'insurance_description',
        'address',
        'telephone_number',
        'post_address',
        'bussiness_line',
        'tax_id',
        'fax_number',
        'stamp_company',
        'signature',
        'letter_head',
    ];
    protected $dates = ['deleted_at'];

    protected $appends = ['insurance_stamp_company', 'signature', 'letter_head'];



    function getInsuranceStampCompanyAttribute()
    {
        $mediaItem = $this->getMedia('insurance_stamp_company')->first();
        $array = [];
        if ($mediaItem) {
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
    function getSignatureAttribute()
    {
        $mediaItem = $this->getMedia('signature')->first();
        $array = [];
        if ($mediaItem) {
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
    function getLetterHeadAttribute()
    {
        $mediaItem = $this->getMedia('letter_head')->first();
        $array = [];
        if ($mediaItem) {
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
