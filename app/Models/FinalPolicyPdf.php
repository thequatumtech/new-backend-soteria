<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalPolicyPdf extends Model
{
    protected $fillable = [
        'policy_id',
        'final_pdf_url',
        'client_id',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(PurchasePolicy::class, 'policy_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}