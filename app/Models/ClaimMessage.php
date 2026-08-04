<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['client_id','claim_id','message','is_message','sent_by','is_read'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
