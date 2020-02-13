<?php
namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
    protected $except = [
        'documento/local/escaneos'
    ];

    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if(! $this -> inExceptArray($request) ){
            return $response -> header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                -> header('Pragma','no-cache')
                -> header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
        }
        
        return $response;
    }
}
