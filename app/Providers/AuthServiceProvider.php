<?php
namespace App\Providers;

use Auth;
use Illuminate\Support\Facades\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Providers\EloquentCustomUserProvider;

use Illuminate\Support\Facades\Cache;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this -> registerPolicies($gate);

        // Binding eloquent.admin to our EloquentAdminUserProvider
        Auth::provider('eloquent.custom', function($app, array $config) {
            return new EloquentCustomUserProvider($app['hash'], $config['model']);
        });

        GateContract::before(function($user, $ability){
            if( $user -> isSuperAdmin() ){
                return true;
            }
        });

        //$permisos = Cache::remember('Permisos.Sistema')

        $permisos = \App\Model\MPermiso::select('SYPE_CODIGO') -> get();
        foreach ($permisos as $permiso) {
            GateContract::define($permiso -> getCodigo(), function($user) use ($permiso){
                return permisoUsuario( $permiso -> getCodigo() );
            });
        }

    }
}