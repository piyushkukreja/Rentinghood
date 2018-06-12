<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $with = ['category'];

    public function getNameAttribute($name) {
        return ucwords(str_replace('_', ' ', $name));
    }

    public function setNameAttribute($name) {
        $name = strtolower(str_replace(' ', '_', $name));
        $this->attributes['name'] = $name;
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }
}
