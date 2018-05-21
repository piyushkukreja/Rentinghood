<?php

namespace App\Http\Controllers\Dashboard;

use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class LendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('mobile_auth');
    }

    public function showLendForm($category_id)
    {
        $data = [];
        $data['categories'] = DB::table('categories')->get();
        $data['category_id'] = $category_id;
        $data['fixhome'] = false;
        $data['category_name'] = DB::table('categories')->where('id', $category_id)->first()->name;
        $data['tab'] = 'lend';
        return view('dashboard.account', $data);
    }

    public function lend(Request $request)
    {

        $rules = [
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'name' => 'required',
            'duration' => 'required',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'file.0' => 'image|max:2000|required'

        ];

        if($request->has('duration'))
        {
            if($request->has('duration') > 0)
                $rules['rate1'] = 'required|integer';
            if($request->has('duration') > 1)
                $rules['rate2'] = 'required|integer';
        }

        $request->validate($rules);
        $rate2 = $request->input('rate2') ? $request->input('rate2') : 0;
        $rate3 = $request->input('rate3') ? $request->input('rate3') : 0;

        $values = [
            'name' => $request->input('name'),
            'subcategory_id' => $request->input('subcategory_id'),
            'lender_id' => Auth::user()->id,
            'availability'=> 1,
            'description' => $request->input('description'),
            'duration' => $request->input('duration'),
            'address' => $request->input('address'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'rate_1' => $request->input('rate1'),
            'rate_2' => $rate2,
            'rate_3' => $rate3,
            'image' => '',
        ];

        $product_id = DB::table('products')->insertGetId($values);
        $original = public_path('/img/uploads/products/original');
        $large = public_path('/img/uploads/products/large');
        $small = public_path('/img/uploads/products/small');

        $i = 0;
        foreach ($request->file as $file) {

            $file = $request->file('file.' . $i);
            $extension = $file->getClientOriginalExtension();
            $file_name = rand(1000, 9999) . sha1(time()) . '.' . $extension;
            $file->move($original, $file_name);
            $i++;
            Image::make($original . '/' . $file_name)->resize(200, 200)->save($small . '/' . $file_name);
            Image::make($original . '/' . $file_name)->resize(500, 500)->save($large . '/' . $file_name);

            $result2 = DB::table('product_pictures')->insert([
                'product_id' => $product_id,
                'file_name' => $file_name,
            ]);
            if($i == 1)
                DB::table('products')->where('id', $product_id)->update(['image' => $file_name]);
        }
        if($product_id != 0 && $result2)
            $request->session()->flash('success', 'Your product was successfully posted.');
        else
            $request->session()->flash('failure', 'Post was unsuccessful');

    }

    public function getProductDetails($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        if($product && ($product->lender_id == Auth::user()->id))
        {
            $subcategory = DB::table('subcategories')->where('id', $product->subcategory_id)->first();
            $product->subcategory_name = $subcategory->name;
            $product->category_name = DB::table('categories')->where('id', $subcategory->category_id)->first()->name;
            $product->message = 'success';
            return json_encode($product);
        }
        else {

            $product = new \stdClass();
            $product->id = 0;
            $product->message = 'failed';
            return json_encode($product);

        }
    }

    public function editPost(Request $request)
    {
        $product = DB::table('products')->where('id', $request->input('id'))->first();
        if((!$product) || (!($product->lender_id == Auth::user()->id)))
        {
            $request->session()->flash('failure', 'You are not authorized to edit this product.');
            return redirect()->route('account', 'inventory');
        }
        $rules = [
            'duration' => 'required',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ];

        if($request->has('duration'))
        {
            if($request->has('duration') > 0)
                $rules['rate1'] = 'required|integer';
            if($request->has('duration') > 1)
                $rules['rate2'] = 'required|integer';
        }

        $request->validate($rules);
        $rate2 = $request->input('rate2') ? $request->input('rate2') : 0;
        $rate3 = $request->input('rate3') ? $request->input('rate3') : 0;

        $values = [
            'description' => $request->input('description'),
            'duration' => $request->input('duration'),
            'address' => $request->input('address'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'rate_1' => $request->input('rate1'),
            'rate_2' => $rate2,
            'rate_3' => $rate3,
        ];

        $result = DB::table('products')->where('id', $request->input('id'))->update($values);

        if($result)
            $request->session()->flash('success', 'Your inventory was successfully updated.');
        else
            $request->session()->flash('failure', 'Update was unsuccessful');

        return redirect()->route('account', 'inventory');
    }
}
