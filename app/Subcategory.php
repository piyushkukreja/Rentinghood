<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public function getNameAttribute($name) {
        return ucwords(str_replace('_', ' ', $name));
    }
}
