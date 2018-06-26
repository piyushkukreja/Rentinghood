<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'address', 'lat', 'lng', 'contact', 'verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin() {
        if($this->privileges === 2) {
            return true;
        }
        return false;
    }


    public function isVendor() {
        if($this->privileges === 1) {
            return true;
        }
        return false;
    }

    public function notes() {
        return $this->hasMany('App\Note')->orderBy('created_at', 'DESC');
    }

    public function inventory() {
        return $this->hasMany('App\Product', 'lender_id');
    }
}
