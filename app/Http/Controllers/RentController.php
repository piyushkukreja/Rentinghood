<?php

namespace App\Http\Controllers;

use App\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Category;
use Illuminate\Support\Facades\Session;

class RentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Shows the rent page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['categories'] = DB::table('categories')->orderBy('name')->get();
        return view('rent.categories', $data);
    }

    public function checkSessionHasLocation()
    {
        $response = [];
        if(Session::has('location') && Session::has('lat') && Session::has('lng'))
            $response['message'] = 'success';
        else
            $response['message'] = 'failed';
        return $response;
    }

    public function getCountAndLocation($category_id)
    {
        $response = [];
        if(Session::has('location') && Session::has('lat') && Session::has('lng'))
        {
            $response['message'] = 'success';
            $lng = Session::get('lng');
            $lat = Session::get('lat');
            $response['location'] = Session::get('location');
            $response['lat'] = $lng;
            $response['lng'] = $lat;
            $response['count'] = DB::table('products')
                ->whereIn('subcategory_id', function ($query) use ($category_id) {
                    $query->select('id')
                        ->from(with(new Subcategory)->getTable())
                        ->where('category_id', $category_id);
                })->where('availability', 1)
                ->where('verified', 1)
                ->whereRaw('haversine(' . $lat . ', ' .$lng . ', products.lat, products.lng) < 10')
                ->groupBy('subcategory_id')
                ->select('subcategory_id', DB::raw('count(*) as total'))
                ->get();
            return $response;
        }
        else
            $response['message'] = 'failed';
        return $response;
    }

    public function getLocation()
    {
        $response = [];
        if(Session::has('location') && Session::has('lat') && Session::has('lng')) {
            $response['message'] = 'success';
            $response['lng'] = Session::get('lng');
            $response['lat'] = Session::get('lat');
            $response['location'] = Session::get('location');
        } else
            $response['message'] = 'failed';
        return $response;

        }

    public function subcategories($category_name)
    {
        $category = DB::table('categories')->where('name', $category_name)->first();
        if(!$category)
            return abort('404');
        $data['subcategories'] = DB::table('subcategories')->where('category_id', $category->id)->orderBy('name')->get();
        $data['categories'] = DB::table('categories')->get();
        $data['category'] = $category;
        return view('rent.subcategories', $data);

    }
    public function subcategory_products(Request $request)
    {
        if(!(Session::has('location') && Session::has('lat') && Session::has('lng')))
            return redirect(route('rent_categories'));
        else {
            $lng = Session::get('lng');
            $lat = Session::get('lat');
        }
        $subcategory_id = $request->input('subcategory_id');
        if($subcategory_id != 0)
            return DB::table('products')
                ->where('subcategory_id', $request->input('subcategory_id'))
                ->where('availability', 1)
                ->where('verified', 1)
                ->whereRaw('haversine(' . $lat . ', ' .$lng . ', products.lat, products.lng) < 10')
                ->orderByRaw('haversine(' . $lat . ', ' .$lng . ', products.lat, products.lng)')
                ->get();
        else {
            $category_id = $request->input('category_id');
            return DB::table('products')
                ->whereIn('subcategory_id', function ($query) use ($category_id) {
                    $query->select('id')
                        ->from(with(new Subcategory)->getTable())
                        ->where('category_id', $category_id);
            })->where('availability', 1)
                ->where('verified', 1)
                ->whereRaw('haversine(' . $lat . ', ' .$lng . ', products.lat, products.lng) < 10')
                ->orderByRaw('haversine(' . $lat . ', ' .$lng . ', products.lat, products.lng)')
                ->get();
        }
    }

    public function showProductDetails($id)
    {

        $product = DB::table('products')->where('id', $id)->first();
        if(!$product || $product->verified == 0)
            abort('404');
        $subcategory = DB::table('subcategories')->where('id', $product->subcategory_id)->first();
        $category = DB::table('categories')->where('id', $subcategory->category_id)->first();
        $product->subcategory = $subcategory;
        $product->category = $category;
        $product_pictures = DB::table('product_pictures')->where('product_id', $id)->get();
        $data['product'] = $product;
        $data['product_pictures'] = $product_pictures;

        return view('rent.product_details', $data);

    }

    public function checkAvailability(Request $request)
    {
        $product_id = $request->input('product_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $transaction = DB::table('transactions')
            ->where('product_id', $product_id)
            ->where('status', '3')
            ->where(function ($query) use($from_date) {
                $query->whereDate('from_date', '<=', $from_date)
                    ->whereDate('to_date', '>=', $from_date);
            })
            ->orWhere(function ($query) use($from_date, $to_date) {
                $query->whereDate('from_date', '<=', $to_date)
                ->whereDate('from_date', '>=', $from_date);;
            })
            ->first();
        if(!$transaction)
        {
            $availability = 1;
        }
        else
        {
            $availability = 0;
        }
        return ['availability' => $availability];
    }

    public function getUnavailableDates(Request $request)
    {
        $product_id = $request->input('product_id');
        $transactions = DB::table('transactions')
            ->where('product_id', $product_id)
            ->where('status', '5')->get();
        $disabled_dates = array();
        $i = 0;
        foreach ($transactions as $transaction)
        {
            $disabled_dates[$i]['from_date'] = $transaction->from_date;
            $disabled_dates[$i]['to_date'] = $transaction->to_date;
            $i++;

        }
        return $disabled_dates;
    }

    public function sendPinCodes(Request $request)
    {
        return DB::table('pin_codes')->where('city_id', $request->input('city_id'))->get();
    }

    public function sendSubcategories(Request $request)
    {
        return DB::table('subcategories')->where('category_id', $request->input('category_id'))->get();
    }
}
