<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentCommission extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'agent_id',
        'line_of_business_id',
        'supervisor_commission'
    ];
}
