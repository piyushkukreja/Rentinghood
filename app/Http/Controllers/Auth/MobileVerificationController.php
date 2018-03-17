<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MobileVerificationController extends Controller
{
    //
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('mobile_guest');

    }

    public function showOTPForm()
    {
        return view('auth.otp');
    }

    public function sendOTP()
    {
        $curl = curl_init();
        $contact = Auth::user()->contact;
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
        $response = curl_exec($curl);
        return $response;
    }

    public function verifyOTP(Request $request)
    {
        $submittedOTP = $request->input('otp');
        $storedOTP = $request->session()->get('otp');
        $response = [];
        if($submittedOTP == $storedOTP)
        {
            $response['status'] = 'success';
            $user = Auth::user();
            $user->verified = 1;
            $user->save();
        }
        else
        {
            $response['status'] = 'failed';
        }
        return $response;
    }

}
