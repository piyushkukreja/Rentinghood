<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $with = ['product', 'renter'];//takes values from db of the value mentioned in $with array

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function renter() {
        return $this->belongsTo('App\User', 'renter_id');
    }

    public function getFromDateAttribute($date)  {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getToDateAttribute($date) {
        return Carbon::parse($date)->format('d/m/Y');
    }
}
