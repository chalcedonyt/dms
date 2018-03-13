<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Carbon\Carbon;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider() {
        \Session::reflash();
        return Socialite::driver('google')
        ->scopes([
            'profile',
            'email',
            'https://www.googleapis.com/auth/drive.readonly',
            'https://www.googleapis.com/auth/spreadsheets.readonly'])
        ->redirect();

    }

     /**
     * Obtain the user information from Google
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request) {
        $google_user = Socialite::driver('google')->user();
        $user = User::where('email', '=', $google_user->getEmail())->first();

        if ($user) {
            $user->name = $google_user->getName();
            $user->google_token_added_at = Carbon::now();
            $user->google_token_expires_at = Carbon::now()->addSeconds($google_user->expiresIn-1);
            $user->google_token = $google_user->token;
            $user->avatar_url = $google_user->avatar;
            $user->save();
            //assign a cookie that is less than the google expiry for now
            \Auth::login($user, $remember = true);

            $redirect = $request->session()->has('redirect')
            ? $request->session()->get('redirect')
            : \Auth::user()->isAdmin()
                ? $this->redirectTo
                : '/';

            $request->session()->forget('redirect');
            return redirect($redirect);
        }
    }

    public function logout() {
        if (\Auth::user()) {
            \Auth::logout(\Auth::user());
        }
        return redirect('/login');
    }
}
