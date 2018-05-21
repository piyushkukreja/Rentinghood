<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductPicture;
use App\User;
use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index() {
        $data = [];
        $data['section'] = 'users';
        return view('layouts.admin_dashboard', ['data' => $data]);
    }

    public function getAllUsers() {
        $response = [];
        $response['data'] = User::all();
        return $response;
    }

    public function usersIndex() {
        $data = [];
        $data['section'] = 'users';
        return view('admin.users_index', ['data' => $data]);
    }

    public function usersUpdate(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        if($request->input('contact') != $user->contact) {
            $user->verfied = 0;
            $user->contact = $request->input('contact');
        }
        $user->address = $request->input('address');
        $user->lat = $request->input('lat');
        $user->lng = $request->input('lng');
        $user->save();
        Session::flash('success', 'User has been updated successfully');
        return redirect('/a/users');
    }

    public function usersShow($id) {
        $user = User::findOrFail($id);

        $data = [];
        $data['section'] = 'users';
        $data['user'] = $user;
        return view('admin.users_view', ['data' => $data]);
    }

    public function notes($id) {
        $user = User::findOrFail($id);

        $response = [];
        $response['data'] = $user->notes;
        $response['status'] = 'success';
        return $response;
    }

    public function notesStore(Request $request, $id) {
        $user = User::findOrFail($id);
        $admin = Auth::user();

        $note = new Note;
        $note->admin_id = $admin->id;
        $note->user_id = $user->id;
        $note->note = $request->input('note')['value'];
        $note->save();
    }

    public function loadInventory($id) {
        $user = User::findOrFail($id);
        $response = [];
        $response['data'] = $user->inventory;
        $response['status'] = 'success';
        return $response;
    }

    public function productsEdit($id) {
        $product = Product::findOrFail($id);
        $data = [];
        $data['section'] = 'products';
        $data['product'] = $product;
        $data['categories'] = Category::pluck('name', 'id')->all();
        $data['subcategories'] = Category::findOrFail($product->category_id)->subcategories->pluck('name', 'id');
        $data['product_pictures'] = $product->pictures;
        return view('admin.products_edit', ['data' => $data]);
    }

    public function productsUpdate(Request $request, $id) {
        $product = Product::findOrFail($id);
        $rules = [
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'name' => 'required',
            'duration' => 'required',
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'rate_1' => 'required|integer'
        ];
        if($request->has('duration'))
        {
            if($request->has('duration') > 0)
                $rules['rate_2'] = 'required|integer';
            if($request->has('duration') > 1)
                $rules['rate_3'] = 'required|integer';
        }

        $request->validate($rules);
        $rate2 = $request->input('rate2') ? $request->input('rate2') : 0;
        $rate3 = $request->input('rate3') ? $request->input('rate3') : 0;

        $product->name = trim($request->input('name'));
        $product->description = trim($request->input('description'));
        $product->subcategory_id = $request->input('subcategory_id');
        $product->duration = $request->input('duration');
        $product->rate_1 = $request->input('rate_1');
        $product->rate_2 = $rate2;
        $product->rate_3 = $rate3;
        $product->address = $request->input('address');
        $product->lat = $request->input('lat');
        $product->lng = $request->input('lng');
        $product->save();
        Session::flash('success', 'Product details have been updated successfully');
        return redirect()->route('products.edit', [$product->id]);
    }

    public function updateDefaultImage(Request $request, $id) {
        $product = Product::findOrFail($id);
        $productPicture = ProductPicture::findorFail((int)$request->input('file_id'));
        $product->image = $productPicture->file_name;
        $product->save();
        return ['status' => 'success'];
    }

    public function removeProductImage(Request $request, $id) {
        $product = Product::findOrFail($id);
        $productPicture = ProductPicture::findorFail((int)$request->input('picture_id'));
        $response = [];
        if($product->image == $productPicture->file_name) {
            $response['status'] = 'failed';
            $response['message'] = 'Cannot remove thumbnail image';
        } else {
            $productPicture->delete();
            $response['status'] = 'success';
        }
        return $response;
    }

}
