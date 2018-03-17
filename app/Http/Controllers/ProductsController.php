<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

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

}
