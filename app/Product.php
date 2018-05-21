<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['name', 'description', ];
    protected $appends = ['category_id'];

    public function getCategoryIdAttribute() {
        return Subcategory::findOrFail($this->subcategory_id)->category_id;
    }

    public function pictures() {
        return $this->hasMany('App\ProductPicture');
    }
}
