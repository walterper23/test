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

        // Binding eloquent.custom to our EloquentCustomUserProvider
        Auth::provider('eloquent.custom', function($app, array $config) {
            return new EloquentCustomUserProvider($app['hash'], $config['model']);
        });

        GateContract::before(function($user, $ability){
            if( $user -> isSuperAdmin() ){
                return true;
            }
        });

        // Recuperamos todos los permisos disponibles en el sistema
        $permisosSistema = Cache::rememberForever('Permisos.Sistema',function(){
            return \App\Model\MPermiso::with('Recurso') -> get();
        });
        
        foreach ($permisosSistema as $permiso) {
            GateContract::define($permiso -> getCodigo(), function($user) use ($permiso){
                return permisoUsuario( $permiso -> getCodigo() );
            });
        }

    }
}