<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSignature extends Model
{
    use HasFactory;

    protected $table = 'user_signature';

    protected $fillable = [
        'purchase_policy_id',
        'client_id',
        'signature',
    ];

    /**
     * Purchase policy relationship
     */
    public function purchasePolicy()
    {
        return $this->belongsTo(PurchasePolicy::class, 'purchase_policy_id');
    }

    /**
     * Client relationship
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
