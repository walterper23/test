<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

/**
 * Controlador para llevar a cabo la restauración de contraseña de los usuarios por medio de su correo electrónico
 */
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
     * Crear nueva instancia del controlador
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Método para indicar la vista a usar para el restablecimiento de contraseña
     */
    public function showLinkRequestForm()
    {
        return view('Auth.Password.email');
    }

    /**
     * Método para llevar a cabo la validación de credenciales del usuario para el restablecimiento de contraseña.
     * En caso de éxito, se enviará un correo con el proceso a continuar para reestablecer la contraseña.
     * En caso contrario, se mostrarán al usuario los errores encontrados durante el intento de restablecimiento de contraseña.
     */
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

    /**
     * Método para validar las credenciales, y el formato de las mismas, del usuario.
     */
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

    /**
     * Método para enviar un mensaje de éxito cuando el restablecimiento de contraseña se haya logrado correctamente
     */
    protected function sendResetLinkResponseEmail($response, $email)
    {
        $status = sprintf('<b>¡Éxito!</b> Se ha enviado un mensaje a <b>%s</b>.<br>Revise su bandeja para continuar el restablecimiento de contraseña.',$email);

        return back()->with('status', $status);
    }

    /**
     * Método para regresar a la página anterior cuando las credenciales de restablecimiento de contraseña no sean correctas.
     * Permite regresar la variable de $errors con la lista de errores especificados
     */
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