<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    //@Override
    public function showLinkRequestForm()
    {
        return view('Auth.Password.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateUsername($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $credentials = [
            'USUA_USERNAME' => $request -> username,
            'USUA_ENABLED'  => 1,
            'USUA_DELETED'  => 0
        ];

        $response = $this->broker()->sendResetLink( $credentials );

        if ($response == Password::RESET_LINK_SENT)
        {
            $email = $this -> broker() -> getUser( $credentials ) -> getEmailForPasswordReset();
            return $this->sendResetLinkResponseEmail($response, $email);
        }
        else
        {
            return $this->sendResetLinkFailedResponse($request, $response);
        }
    }

    protected function validateUsername(Request $request)
    {
        $this->validate($request,
            ['username' => 'required|email'],
            [
                'username.required' => 'Introduzca su nombre de usuario',
                'username.email'    => 'Introduzca un correo electrónico válido'
            ]
        );
    }

    protected function sendResetLinkResponseEmail($response, $email)
    {
        $status = sprintf('<b>¡Éxito!</b> Se ha enviado un mensaje a <b>%s</b>.<br>Revise su bandeja para continuar el restablecimiento de contraseña.',$email);

        return back()->with('status', $status);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['username' => 'El usuario es incorrecto o no existe. Intente de nuevo']
        );
    }

    public function broker()
    {
        return Password::broker();
    }
}