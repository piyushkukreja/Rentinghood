<?php

namespace App\Http\Controllers\Dashboard;
use App\Event;
use App\Transaction;
use App\User;
use App\Product;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('mobile_auth');
    }

    /**
     * Show the application dashboard.
     *
     *
     */
    public function index($tab = 'undefined')
    {
        $data = [];
        if ($tab == 'undefined')
        {
            $data['fixhome'] = true;
            $data['tab'] = 'profile';
        }
        else
        {
            $data['fixhome'] = false;
            $data['tab'] = $tab;
        }
        $data['categories'] = DB::table('categories')->get();

        return view('dashboard.account', $data);
    }


    /*public function vendorIndex($tab = 'undefined')
    {

        $data = [];
        if ($tab == 'undefined')
        {
            $data['fixhome'] = true;
            $data['tab'] = 'inventory';
        }
        else
        {
            $data['fixhome'] = false;
            $data['tab'] = $tab;
        }
        $data['categories'] = DB::table('categories')->get();

        return view('dashboard.account', $data);

    }*/

    public function getInventory()
    {
        return DB::table('products')->where('lender_id', Auth::user()->id)->get();
    }

    public function updateAvailability(Request $request)
    {
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();
        if($product->lender_id == Auth::user()->id || Auth::user()->isAdmin())
        {
            DB::table('products')->where('id', $product->id)->update(['availability' => $request->input('availability')]);
            return ['message' => 'success'];
        }
        return ['message' => 'failed'];
    }

    public function getMessages()
    {

        $response = [];
        $response['old_requests'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'renter_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'first_name', 'last_name', 'name', 'products.id as pid', 'status')
            ->where('lender_id', Auth::user()->id)
            ->whereIn('status', [2, 3, 4, 5])
            ->get();
        $response['new_requests'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'renter_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'first_name', 'last_name', 'name', 'products.id as pid', 'status')
            ->where('lender_id', Auth::user()->id)
            ->where('status', 1)
            ->get();

        $response['old_replies'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'lender_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'name', 'products.id as pid', 'status')
            ->where(function($query) {
              $query->where(function ($query) {
                  $query->where('status', 3)
                      ->where('seen', true);
              })->orWhereIn('status', [1, 4, 5]);
            })
            ->where('renter_id', Auth::user()->id)
            ->get();

        $response['new_replies'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'lender_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'name', 'products.id as pid', 'status')
            ->where('renter_id', Auth::user()->id)
            ->whereIn('status', [2, 3])
            ->where('seen', false)
            ->get();

        return $response;

    }

    public function getMessageCount()
    {
        $response = [];
        $response['requests_count'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'renter_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'first_name', 'last_name', 'name', 'products.id as pid', 'status')
            ->where('lender_id', Auth::user()->id)
            ->where('status', 1)
            ->count();

        $response['replies_count'] = DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'lender_id')
            ->select('transactions.id as tid', 'from_date', 'to_date', 'users.id as uid', 'name', 'products.id as pid', 'status')
            ->where('renter_id', Auth::user()->id)
            ->whereIn('status', [2, 3])
            ->where('seen', false)
            ->count();

        return $response;
    }

    public function answerRequest(Request $request)
    {
        $transaction = Transaction::find($request->input('tid'));
        if($request->input('reply') == 0)
            $status = 2;
        else {
            $status = 3;
            $this->createEvents($transaction);
        }
        $product = Product::find($transaction->product_id);
        $response = [];
        if($product->lender_id == Auth::user()->id) {
            $transaction->status = $status;
            $transaction->save();
            $renter = User::find($transaction->renter_id);
            if($status == 3) {
                $response['contact'] = $renter->contact;
                $message = 'Hey Neighbour, your request for ' . $product->name . ' is approved. Please visit your dashboard to get contact details of owner';
            }
            else
                $message = 'Hey Neighbour, the owner couldn\'t fulfill your request for ' . $product->name . '. We apologize for the same.';
            $response['message'] = 'success';

            $mobile = $renter->contact;
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

            //$result = curl_exec($curl);
            //$err = curl_error($curl);

            //curl_close($curl);
        }
        else
            $response['message'] = 'failed';
        return $response;

    }

    private function createEvents($transaction) {
        $prep_event = new Event;
        $start_event = new Event;
        $end_event = new Event;

        $prep_event->title = $transaction->product->name . ' - Preparation';
        $start_event->title = $transaction->product->name . ' - Delivery';
        $end_event->title = $transaction->product->name . ' - Collection';

        $format = 'd/m/Y';

        $start_date = Carbon::createFromFormat($format, $transaction->from_date);
        $end_date = Carbon::createFromFormat($format, $transaction->to_date);
        $prep_date = $start_date->copy()->subDays(2);

        $start_event->date = $start_date->toDateString();
        $prep_event->date = $prep_date->toDateString();
        $end_event->date = $end_date->toDateString();

        $prep_event->type = 1;
        $start_event->type = 2;
        $end_event->type = 3;

        $prep_event->color = 'fe8a71';
        $start_event->color = '3da4ab';
        $end_event->color = 'f6cd61';

        $prep_event->transaction_id = $start_event->transaction_id = $end_event->transaction_id = $transaction->id;
        $prep_event->vendor_id = $start_event->vendor_id = $end_event->vendor_id = $transaction->product->lender_id;

        $prep_event->save();
        $start_event->save();
        $end_event->save();
    }

    public function replySeen(Request $request)
    {
        $transaction = DB::table('transactions')->where('id', $request->input('tid'))->first();
        if($transaction && ($transaction->renter_id == Auth::user()->id))
        {
            DB::table('transactions')->where('id', $transaction->id)->update(['seen' => true]);
            return ['message' => 'success'];
        }
        return ['message' => 'failed'];
    }

    public function getContact(Request $request)
    {
        $response = [];
        $transaction = DB::table('transactions')->where('id', $request->input('tid'))->first();
        $renter = DB::table('users')->where('id', $transaction->renter_id)->first();
        $product = DB::table('products')->where('id', $transaction->product_id)->first();
        $lender = DB::table('users')->where('id', $product->lender_id)->first();
        if(Auth::user()->id == $renter->id)
        {
            $response['contact'] = $lender->contact;
            $response['last_name'] = $lender->last_name;
            $response['first_name'] = $lender->first_name;
            $response['message'] = 'success';
        }
        else if(Auth::user()->id == $lender->id)
        {
            $response['contact'] = $renter->contact;
            $response['message'] = 'success';
        }
        else
        {
            $response['message'] = 'failed';
        }
        return $response;
    }

    public function getNotification()
    {

        return json_encode(DB::table('products')
            ->join('transactions', 'products.id', '=', 'product_id')
            ->join('users', 'users.id', '=', 'renter_id')
            ->select('transactions.id as tid', 'users.id as user_id', 'first_name', 'last_name', 'name', 'products.id as p_product_id', 'status', 'image')
            ->where('lender_id', Auth::user()->id)
            ->where('status', 3)
            ->first());

    }

    public function replyNotification(Request $request)
    {

        if($request->input('reply') == '0')
            $status = 4;
        else
            $status = 5;
        $transaction = DB::table('transactions')->where('id', $request->input('id'))->first();
        $product = DB::table('products')->where('id', $transaction->product_id)->first();
        if($product->lender_id == Auth::user()->id)
        {
            DB::table('transactions')->where('id', $request->input('id'))->update(['status' => $status] );
            return ['message' => 'success'];
        }
        return ['message' => 'failed'];

    }

}
