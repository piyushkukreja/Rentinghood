<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function isAdmin() {
        if($this->privileges >= 2) {
            return true;
        }
        return false;
    }

    public function isVendor() {
        if($this->privileges >= 1) {
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

    public function events() {
        return $this->hasMany('App\Event', 'vendor_id');
    }

    public function transactions() {
        return Transaction::whereHas('product', function ($query) {
                $query->where('lender_id', \Auth::user()->id);
            })
            ->where('status', '=', "1")
            ->get();
    }

    public function newOrdersCount() {
        $new_orders = Transaction::whereHas('product', function ($query) {
                $query->where('lender_id', \Auth::user()->id);
            })
            ->where('status', '=', "1")
            ->get();
        return count($new_orders);
    }
}
