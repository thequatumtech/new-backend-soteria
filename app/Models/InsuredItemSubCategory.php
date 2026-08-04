<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuredItemSubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','insured_item_categories_id'];

    public function category(){
        return $this->belongsTo(InsuredItemCategory::class, 'insured_item_category_id');
    }

}
