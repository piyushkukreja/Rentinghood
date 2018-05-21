<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{
    //
    protected $with = ['admin'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function admin() {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('d/m/Y h:i A');
    }
}
