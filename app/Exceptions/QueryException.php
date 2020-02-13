<?php

namespace App\Exceptions;

use Exception;

class QueryException extends Exception
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
    public static function render($request, $exception)
    {
        if( $exception -> getCode() == 2002 )
            return view('errors.exceptions.QueryException');

        return false;
    }
}