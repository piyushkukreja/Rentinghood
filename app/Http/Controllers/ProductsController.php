<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;

class ProductsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function contactOwner(Request $request)
    {
        $user = Auth::user();
        if($user->verified == 1) {
            $product = DB::table('products')->where('id', $request->input('product_id'))->first();
            $lender = DB::table('users')->where('id', $product->lender_id)->first();
            $user = \Auth::user();
            $message = 'Hey neighbour. You have a new request for ' . $product->name . '. Please visit your dashboard to approve';
            $mobile = $lender->contact;

            $curl = curl_init();

            $authkey = '196622AOmNFnJN5a774bc9';
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=RENTHD&route=4&mobiles=" . $mobile . "&authkey=" . $authkey . "&country=91&message=" . urlencode($message),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $from_date = DateTime::createFromFormat('d-m-Y', $request->input('from_date'));
            $to_date = DateTime::createFromFormat('d-m-Y', $request->input('to_date'));

            $transaction_values = array(

                'renter_id' => \Auth::user()->id,
                'product_id' => $product->id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'status' => 1,

            );
            DB::table('transactions')->insert($transaction_values);
            return array('verified' => true, 'name' => $lender->first_name . ' ' . $lender->last_name);

        }
        else {

            return array('verified' => false);

        }
    }

    public function checkForPlacedRequest(Request $request)
    {
        $user = Auth::user();
        $transaction = DB::table('transactions')
            ->where('product_id', $request->input('product_id'))
            ->where('renter_id', \Auth::user()->id)
            ->where('from_date', DateTime::createFromFormat('d-m-Y', $request->input('from_date'))->format('Y-m-d'))
            ->where('to_date', DateTime::createFromFormat('d-m-Y', $request->input('to_date'))->format('Y-m-d'))
            ->first();
        if($transaction)
            return ['placed' => true, 'from_date' => DateTime::createFromFormat('d-m-Y', $request->input('from_date'))];
        else
            return ['placed' => false, 'from_date' => DateTime::createFromFormat('d-m-Y', $request->input('from_date'))];

    }

    public function productsBulk()
    {
        $data = [];
        $data['section'] = 'products-bulk';
        return view('admin.products_bulk', ['data' => $data]);
    }

    public function productsUpload(Request $request)
    {
        $request->validate([
            'lender_id' => 'required|integer',
            'import_file' => 'required|file'
        ]);
        $productIds = [];
        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get();
        if(!empty($data) && $data->count()) {
            foreach ($data as $key => $value) {
                if($value->name != "") {
                    $simplified_name = $value->name;
                    $simplified_name = preg_replace("/[^A-Za-z0-9]/", '', $simplified_name);

                    $product = new Product;
                    $product->name = $value->name;
                    $product->subcategory_id = intval($value->subcategory_id);
                    $product->availability = 1;
                    $product->description = $value->description;
                    $product->duration = '2';
                    $product->lender_id = intval($request->input('lender_id'));
                    $product->rate_1 = intval($value->rate_1);
                    $product->rate_2 = intval($value->rate_2);
                    $product->rate_3 = intval($value->rate_3);
                    $product->address = $value->address;
                    $product->lat = floatval(str_replace("° N", "", $value->lat));
                    $product->lng = floatval(str_replace("° E", "", $value->lng));
                    $product->image = '';
                    $product->verified = 1;
                    $product->save();

                    $productIds[$simplified_name] = $product->id;
                }
            }

            $original = public_path('/img/uploads/products/original');
            $large = public_path('/img/uploads/products/large');
            $small = public_path('/img/uploads/products/small');

            $i = 0;
            foreach ($request->file as $file) {
                $file = $request->file('file.' . $i);
                $extension = $file->getClientOriginalExtension();
                $original_name = str_replace('.' . $extension, '', $file->getClientOriginalName());
                $product_name = substr($original_name, 0, strlen($original_name) - 1);
                $image_number = intval(substr($original_name, strlen($original_name) - 1, 1));

                //get Product ID from filename
                $product = Product::find($productIds[$product_name]);
                if(!$product) {
                    continue;
                }

                $file_name = rand(1000, 9999) . sha1(time()) . '.' . $extension;
                $file->move($original, $file_name);

                //Resizing and saving image
                $canvasSmall = Image::canvas(200, 200);
                $canvasBig = Image::canvas(500, 500);

                $imageSmall  = Image::make($original . '/' . $file_name)->resize(200, 200, function($constraint) {
                    $constraint->aspectRatio();
                });
                $imageBig  = Image::make($original . '/' . $file_name)->resize(500, 500, function($constraint) {
                    $constraint->aspectRatio();
                });

                $canvasSmall->insert($imageSmall, 'center');
                $canvasBig->insert($imageBig, 'center');

                $canvasSmall->save($small . '/' . $file_name);
                $canvasBig->save($large . '/' . $file_name);
                /*Image::make($original . '/' . $file_name)->resize(200, 200)->save($small . '/' . $file_name);
                Image::make($original . '/' . $file_name)->resize(500, 500)->save($large . '/' . $file_name);*/

                $product_picture = new ProductPicture;
                $product_picture->product_id = $product->id;
                $product_picture->file_name = $file_name;
                $product_picture->save();

                if($image_number == 1) {
                    $product->image = $file_name;
                    $product->save();
                }
                $i++;
            }
        }
        return back();
    }

}
