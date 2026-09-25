<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewalPolicy extends Model
{
    use HasFactory;

    protected $table = 'renewal_policies';

    protected $fillable = [
        'old_policy_id',
        'new_policy_id',
    ];

    public function oldPolicy()
    {
        return $this->belongsTo(
            PurchasePolicy::class,
            'old_policy_id'
        );
    }

    public function newPolicy()
    {
        return $this->belongsTo(
            PurchasePolicy::class,
            'new_policy_id'
        );
    }
}