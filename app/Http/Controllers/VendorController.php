<?php

namespace app\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductPicture;
use App\Subcategory;
use App\User;
use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VendorController
{
    public function index() {
        $data = [];
        $data['section'] = 'products-bulk';
        return view('layouts.vendor_dashboard', ['data' => $data]);
    }

    public function newOrders()
    {
        $data = [];
        $data['section'] = 'new-orders';
        return view('vendor.new_orders',['data' => $data]);
    }

    public function getNewOrders()
    {
        $response = [];
        $response['data'] = Auth::user()->transactions();
        return $response;
    }

    public function inventory()
    {
        $data = [];
        $data['section'] = 'inventory';
        return view('vendor.inventory',['data' => $data]);
    }

    public function loadInventory() {
        $user = Auth::user();
        $response = [];
        $response['data'] = $user->inventory;
        $response['status'] = 'success';
        return $response;
    }
}