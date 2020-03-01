<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Controlador para llevar a cabo el proceso de autenticación de los usuarios
 */
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
     * Crear nueva instancia del controlador.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Método para indicar la vista con el formulario para que los usuarios puedan iniciar sesión.
     */
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    /**
     * Método para validar las credenciales, y el formato de las mismas, del usuario. 
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Método para indicar el atributo "name" del campo del formulario que es utilizado por el usuario para proporcionar su nombre de usuario.
     */
    protected function username(){
        return 'username';
    }

    /**
     * Método para retornar el valor de las credenciales del usuario.
     */
    protected function credentials(Request $request)
    {
        $credentials = [
            'USUA_USERNAME' => $request->username,
            'USUA_PASSWORD' => $request->password,
            'USUA_ENABLED'  => 1,
            'USUA_DELETED'  => 0,
        ];

        if( $request->username == 'super.admin.sigesd' ){

            unset($credentials['USUA_ENABLED']);
            unset($credentials['USUA_DELETED']);
        }

        return $credentials;
    }

    /**
     * Método para indicar las siguientes instrucciones a realizar cuando el usuario inicie sesión exitosamente.
     */
    protected function authenticated(Request $request, $user)
    {
        $user->USUA_LAST_LOGIN   = is_null($user->getRecentLogin()) ? \Carbon\Carbon::now() : $user->getRecentLogin();
        $user->USUA_RECENT_LOGIN = \Carbon\Carbon::now();
        $user->save();

        session(['Direcciones'=>$user->Direcciones]);
        session(['DireccionesKeys'=>$user->Direcciones->pluck('DIRE_DIRECCION')->toArray()]);

        session(['Departamentos'=>$user->Departamentos]);
        session(['DepartamentosKeys'=>$user->Departamentos->pluck('DEPA_DEPARTAMENTO')->toArray()]);

    }
}
