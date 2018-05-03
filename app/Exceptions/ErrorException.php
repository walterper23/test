<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public static function render_($request)
    {
        return view('Auth.login');
    }
}