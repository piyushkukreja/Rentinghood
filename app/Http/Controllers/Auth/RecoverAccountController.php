<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecoverAccountController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.passwords.mobile');
    }

    public function recover(Request $request)
    {
        $request->validate([
            'contact' => 'required|digits:10'
        ]);

        $user = DB::table('users')->where('contact', $request->input('contact'))->first();
        $response = [];
        if($user)
        {
            $this->sendOTP($user->contact);
            Session::put('reset_contact', $user->contact);
            $response['exists'] = true;
        }
        else
        {
            $response['exists'] = false;
        }
        return $response;

    }

    public function sendOTP($contact)
    {
        $curl = curl_init();
        $otp = rand(1000, 9999);
        Session::put('otp', $otp);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://control.msg91.com/api/sendotp.php?authkey=196622AOmNFnJN5a774bc9&sender=RENTHD&mobile=91{$contact}&otp={$otp}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        curl_exec($curl);
    }

    public function verifyOTP(Request $request)
    {
        $submittedOTP = $request->input('otp');
        $storedOTP = $request->session()->get('otp');
        $response = [];
        if($submittedOTP == $storedOTP)
        {
            $contact = $request->session()->get('reset_contact');
            $user = DB::table('users')->where('contact', $contact)->first();
            $reset_code = rand(10000000, 99999999) . $user->contact;
            DB::table('password_resets')->insert(['email' => $user->email, 'token' => $reset_code]);
            $response['status'] = 'success';
            $response['reset_code'] = $reset_code;
        }
        else
        {
            $response['status'] = 'failed';
        }
        return $response;
    }

    public function resetPassword(Request $request)
    {
        $request->validate([

            'password' => 'required|string|min:6|confirmed',

        ]);

        $response = [];
        $reset_code = $request->input('reset_code');
        $password_reset = DB::table('password_resets')->where('token', $reset_code)->first();
        if($password_reset)
        {
            DB::table('users')->where('email', $password_reset->email)->update(['password' => bcrypt($request->input('password'))]);
            $response['status'] = 'success';
        }
        else
        {
            $response['status'] = 'failed';
        }
        return $response;
    }
}
