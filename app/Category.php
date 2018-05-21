<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getNameAttribute($name) {
        return ucwords(str_replace('_', ' ', $name));
    }

    public function subcategories() {
        return $this->hasMany('App\Subcategory');
    }
}
