<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    protected $appends = ['full_name'];

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

    public function hasContact() {
        if(strlen((string)$this->contact) == 10)
            return true;
        else
            return false;
    }

    public function hasContactVerified() {
        if($this->verified == 1)
            return true;
        else
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
        $new_orders_count = Transaction::whereHas('product', function ($query) {
                $query->where('lender_id', \Auth::user()->id);
            })->where('status', 1)->count();
        return $new_orders_count;
    }

    public function newPostsCount() {
        if($this->isAdmin()) {
            $new_posts_count = Product::where('verified', 0)->count();
            return $new_posts_count;
        }
        return 0;
    }

    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
