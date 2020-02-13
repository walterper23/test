<?php

namespace App\Http\Middleware;

use Closure;

class QueryListenLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if( config('app.debug') ){
            \DB::listen(function($query){
                \Illuminate\Support\Facades\Log::info($query->sql);
                \Illuminate\Support\Facades\Log::info($query->bindings);
                \Illuminate\Support\Facades\Log::info('');
            });
        }

        return $next($request);
    }
}
