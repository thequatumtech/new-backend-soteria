<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contact_us';
    protected $dates = ['deleted_at'];
    protected $fillable = ['mobile', 'message','email', 'address','contact_name','clientid', 'created_at', 'updated_at'];

}
