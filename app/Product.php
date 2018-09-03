<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $appends = ['category_id', 'category'];
    protected $with = array('subcategory', 'lender');

    public function getCategoryIdAttribute() {
        return $this->category()->id;
    }

    public function getCategoryAttribute() {
        return $this->category()->name;
    }

    public function category() {
        return Category::findOrFail($this->subcategory->category_id);
    }

    public function subcategory() {
        return $this->belongsTo('App\Subcategory');
    }

    public function pictures() {
        return $this->hasMany('App\ProductPicture');
    }

    public function lender() {
        return $this->belongsTo('App\User', 'lender_id');
    }

    public function transactions() {
        return $this->hasMany('App\Transaction');
    }
}
