<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('Auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

    protected function username(){
        return 'username';
    }

    protected function credentials(Request $request)
    {

        $credentials = [
            'USUA_USERNAME' => $request -> username,
            'USUA_PASSWORD' => $request -> password,
            'USUA_ENABLED'  => 1,
            'USUA_DELETED'  => 0,
        ];

        if( $request -> username == 'super.admin'  ){
            unset($credentials['USUA_ENABLED']);
            unset($credentials['USUA_DELETED']);
        }

        return $credentials;
    }

    protected function authenticated(Request $request, $user)
    {
        $user -> USUA_LAST_LOGIN   = is_null($user -> getRecentLogin()) ? \Carbon\Carbon::now() : $user -> getRecentLogin();
        $user -> USUA_RECENT_LOGIN = \Carbon\Carbon::now();
        $user -> save();
    }
}
