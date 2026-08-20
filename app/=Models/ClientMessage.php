<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientMessage extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['client_id','message'];

    public function client()
    {
        return $this->belongsTo(Client::class,'id','client_id');
    }

}
