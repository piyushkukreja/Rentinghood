<?php

namespace App\Http\Controllers;

use App\Subcategories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Categories;

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

    public function subcategories($category_name)
    {
        $category = DB::table('categories')->where('name', $category_name)->first();
        if(!$category)
            return '';
        $data['subcategories'] = DB::table('subcategories')->where('category_id', $category->id)->orderBy('name')->get();
        $data['categories'] = DB::table('categories')->get();
        $data['category'] = $category;
        return view('rent.subcategories', $data);

    }
    public function subcategory_products(Request $request)
    {
        $subcategory_id = $request->input('subcategory_id');
        if($subcategory_id != 0)
            return DB::table('products')->where('subcategory_id', $request->input('subcategory_id'))->where('availability', 1)->get();
        else {
            $category_id = $request->input('category_id');
            return DB::table('products')->whereIn('subcategory_id', function ($query) use ($category_id) {
                $query->select('id')->from(with(new Subcategories)->getTable())->where('category_id', $category_id);
            })->where('availability', 1)->get();
        }
    }

    public function showProductDetails($id)
    {

        $product = DB::table('products')->where('id', $id)->first();
        if(!$product)
            abort('404');
        $subcategory = DB::table('subcategories')->where('id', $product->subcategory_id)->first();
        $category = DB::table('categories')->where('id', $subcategory->category_id)->first();
        if(!\Auth::user())
            $data['cities'] = DB::table('cities')->get();
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
