<?php

namespace App\Http\Controllers\Dashboard;

use App\Product;
use App\ProductPicture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class LendController extends Controller
{
    public function index() {
        $data = [];
        $data['categories'] = DB::table('categories')->orderBy('name')->get();
        return view('lend.categories', $data);
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

        $product = new Product;
        $product->name = $request->input('name');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->lender_id = Auth::user()->id;
        $product->availability= 1;
        $product->description = $request->input('description');
        $product->duration = $request->input('duration');
        $product->address = $request->input('address');
        $product->lat = $request->input('lat');
        $product->lng = $request->input('lng');
        $product->rate_1 = $request->input('rate1');
        $product->rate_2 = $rate2;
        $product->rate_3 = $rate3;
        $product->image = '';
        $product->verified = 0;
        $product->save();

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

            $product_picture = new ProductPicture;
            $product_picture->product_id = $product->id;
            $product_picture->file_name = $file_name;
            $product_picture->save();

            if($i == 1) {
                $product->image = $file_name;
                $product->save();
            }
        }
        $request->session()->flash('success', 'Your product was successfully posted.');
    }

    public function getProductDetails($id)
    {
        $user = Auth::user();
        $product = Product::find($id);
        if($product && ($product->lender_id == $user->id))
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
        $product = Product::findOrFail($request->input('id'));
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

        $product->description = $request->input('description');
        $product->duration = $request->input('duration');
        $product->address = $request->input('address');
        $product->lat = $request->input('lat');
        $product->lng = $request->input('lng');
        $product->rate_1 = $request->input('rate1');
        $product->rate_2 = $rate2;
        $product->rate_3 = $rate3;
        $product->save();

        $request->session()->flash('success', 'Your inventory was successfully updated.');
        return redirect()->route('account', 'inventory');
    }
}
