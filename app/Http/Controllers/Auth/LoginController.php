<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your dashboard screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user)
    {
        Session::put('location', $user->address);
        Session::put('lat', $user->lat);
        Session::put('lng', $user->lng);
        if ($request->ajax()) {
            return response()->json([
                'message' => 'success',
                'contact' => Auth::user()->contact,
            ]);
        } else {
            return redirect()->intended(route('home'));
        }
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider. Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return \Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authenticated_user = $this->findOrCreateUser($user, $provider);
        if(isset($authenticated_user->address)) {
            Auth::login($authenticated_user, true);
            return redirect()->intended(route('home'));
        } else {
            return redirect()->route('contact');
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authenticated_user = User::where('provider_id', $user->id)->first();
        if ($authenticated_user)
            return $authenticated_user;

        $new_user = new User;
        $names = explode(' ', $user->name);
        $new_user->first_name = $names[0];
        $new_user->last_name = count($names) > 1 ? $names[1] : ' ';
        $new_user->email = $user->email;
        $new_user->provider = $provider;
        $new_user->provider_id = $user->id;
        $new_user->verified = 0;
        $new_user->address = '';
        $new_user->lat = 0;
        $new_user->lng = 0;

        $new_user->contact = null;

        $new_user->save();
        return $new_user;
    }
}
