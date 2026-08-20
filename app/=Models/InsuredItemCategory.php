<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuredItemCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function sub_categories()
    {
        return $this->hasMany(InsuredItemSubCategory::class,'insured_item_category_id');
    }

}
