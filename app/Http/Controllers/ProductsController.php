<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

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

    public function importExport()
    {
        return view('importExport');
    }

    public function importExcel()
    {
        if(Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {})->get();
            if(!empty($data) && $data->count()) {
                foreach ($data as $key => $value) {
                    if($value->name != "") {
                        $temp = str_replace(" ", "", $value->name);
                        $temp = str_replace("'", "_", $temp);
                        $temp = str_replace("&", "_", $temp);
                        $temp = str_replace(".", "", $temp);
                        $file_name = $temp . '.jpg';
                        $file_name1 = $temp . '1.jpg';
                        $product = ['name' => $value->name, 'subcategory_id' => intval($value->subcategory_id), 'availability' => 1,
                            'description' => (trim($value->authors) != '' ? 'By ' . $value->authors . '<br>' : '') . $value->description, 'duration' => '2',
                            'lender_id' => 4,
                            'rate_1' => intval($value->rate_1), 'rate_2' => intval($value->rate_2), 'rate_3' => intval($value->rate_3),
                            'address' => $value->address, 'lat' => floatval(str_replace("° N", "", $value->lat)), 'lng' => floatval(str_replace("° E", "", $value->lng)),
                            'image' => $file_name];
                        $product_id = DB::table('products')->insertGetId($product);
                        $product_pictures = ['product_id' => $product_id, 'file_name' => $file_name];
                        DB::table('product_pictures')->insert($product_pictures);
                        if (intval($value->image_num) == 2) {
                            $product_pictures = ['product_id' => $product_id, 'file_name' => $file_name1];
                            DB::table('product_pictures')->insert($product_pictures);
                        }
                    }
                }
            }
        }
        return back();
    }

}
