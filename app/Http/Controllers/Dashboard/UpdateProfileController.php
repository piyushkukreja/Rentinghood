<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

class UpdateProfileController extends Controller
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
     * Show the profile page (form).
     *
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {

        $request->session()->flash('failure', 'Some fields were filled incorrectly.');
        $user = \Auth::user();
        if ($request->has('submit_profile'))
        {
            $request->validate([

                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'city_id' => 'required|integer',
                'pin_code_id' => 'required|integer',

            ]);
            $request->session()->remove('failure');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->city_id = $request->input('city_id');
            $user->pin_code_id = $request->input('pin_code_id');
            if ($result = $user->save())
                $request->session()->flash('success', 'Your profile was successfully updated.');
            else
                $request->session()->flash('failure', 'Your profile could not be updated.');

            return redirect()->route('account', 'profile');
        }
        elseif ($request->has('submit_password'))
        {
            $request->validate([

                'old_password' => 'required|string|min:6',
                'password' => 'required|string|min:6|confirmed',

            ]);
            if(Hash::check($request->input('old_password') , $user->password))
            {
                $user->password = bcrypt($request->input('password'));
                if ($result = $user->save())
                    $request->session()->flash('success', 'Your password was successfully updated.');
                else
                    $request->session()->flash('failure', 'Your password could not be updated.');

            }
            else
            {
                $errors = new MessageBag();
                $errors->add('old_password', 'Incorrect password.');
                return redirect()->route('account', 'password')->withErrors($errors);
            }
        }


        return redirect()->route('account');

    }

    public function sendPinCodes(Request $request)
    {
        return DB::table('pin_codes')->where('city_id', $request->input('city_id'))->get();
    }

}
