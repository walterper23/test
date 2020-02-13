<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Access\AuthorizationException;

use Closure;
use Exception;

class CanAtLeast extends Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $ability, ...$abilities)
    {
        $this->auth->authenticate();

        array_unshift($abilities,$ability);

        $last = end($abilities);

        foreach ($abilities as $ability) {
            try {

                $this->gate->authorize($ability, $this->getGateArguments($request, []));
                
                return $next($request);
            } catch(Exception $error) {
                if( $ability === $last )
                {
                    throw new AuthorizationException($error->getMessage());
                }
            }
        }

    }
}
