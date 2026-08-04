<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChronicDisease extends Model
{
    use HasFactory;
    use HasFactory;
    use SoftDeletes;
    
    public $fillable = ['name'];
}
