<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
        if ($request->expectsJson()) {
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
     * @param Request $request
     * @param $provider
     *
     * @return Response
     */
    public function redirectToProvider(Request $request, $provider)
    {
        if($request->has('post_login_url'))
            Session::put('post_login_url', $request->input('post_login_url'));
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
        Auth::login($authenticated_user, true);
        if(Session::has('post_login_url')) {
            $redirect_url = Session::get('post_login_url');
            Session::forget('post_login_url');
            return redirect($redirect_url);
        }
        return redirect()->intended(route('home'));
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

        $existing_user = User::where('email', $user->email)->first();
        if($existing_user) {
            if(!$existing_user->hasVerifiedEmail()) {
                $existing_user->password = null;
                $existing_user->contact = null;
                $existing_user->verified = 0;
            }
            $existing_user->provider = $provider;
            $existing_user->provider_id = $user->id;
            $existing_user->email_verified_at = new Carbon;
            $existing_user->save();
            return $existing_user;
        }

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
        $new_user->email_verified_at = new Carbon;

        $new_user->contact = null;

        $new_user->save();
        return $new_user;
    }
}
