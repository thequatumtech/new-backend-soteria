<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'redirect_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
