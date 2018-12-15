<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
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
    public static function render($request)
    {

        if ( $request->ajax() )
        {
            return response()->json(['status'=>false,'message'=>'Recurso no disponible']);
        }
        else
        {
            return view('errors.exceptions.ModelNotFoundException');
        }

    }
}