<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getNameAttribute($name) {
        return ucwords(str_replace('_', ' ', $name));
    }

    public function setNameAttribute($name) {
        $name = strtolower(str_replace(' ', '_', $name));
        $this->attributes['name'] = $name;
    }

    public function getIsDisabledAttribute($value) {
        return intval($value);
    }

    public function subcategories() {
        return $this->hasMany('App\Subcategory');
    }
}
