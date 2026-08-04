<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\PurchasePolicy;
class PolicyMailLog extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'purchase_policy_id', 'client_mail', 'agent_mail'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function policy()
    {
        return $this->belongsTo(PurchasePolicy::class, 'purchase_policy_id');
    }
}
