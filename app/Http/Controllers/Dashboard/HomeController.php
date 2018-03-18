<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
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

    public function getInventory()
    {
        return DB::table('products')->where('lender_id', Auth::user()->id)->get();
    }

    public function updateAvailability(Request $request)
    {
        $product = DB::table('products')->where('id', $request->input('product_id'))->first();
        if($product->lender_id == Auth::user()->id)
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
            ->where(function ($query) {
                $query->where('status', 3)
                    ->where('seen', true);
            })
            ->orWhereIn('status', [1, 4, 5])
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
            ->whereIn('status', [2, 3, 4, 5])
            ->where('seen', false)
            ->count();

        return $response;
    }

    public function answerRequest(Request $request)
    {

        if($request->input('reply') == '0')
            $status = 2;
        else
            $status = 3;
        $transaction = DB::table('transactions')->where('id', $request->input('tid'))->first();
        $product = DB::table('products')->where('id', $transaction->product_id)->first();
        $response = [];
        if($product->lender_id == Auth::user()->id)
        {
            DB::table('transactions')->where('id', $request->input('tid'))->update(['status' => $status] );
            if($status == 3)
            {
                $user = DB::table('users')->where('id', $transaction->renter_id)->first();
                $response['contact'] = $user->contact;
            }
            $response['message'] = 'success';
        }
        else
            $response['message'] = 'failed';
        return $response;

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
