<?php
/**
 * Created by PhpStorm.
 * User: PIYUSH
 * Date: 25-Jun-18
 * Time: 10:11 PM
 */

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
}