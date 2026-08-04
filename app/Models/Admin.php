<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'admins';

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->third_name. ' ' . $this->last_name;
    }

    /**
     * Get the admin_id as zero-padded.
     *
     * @return string
     */
    public function getAdminIdAttribute($value)
    {
        return str_pad($value, 4, '0', STR_PAD_LEFT);
    }
}
