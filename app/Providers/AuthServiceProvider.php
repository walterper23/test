<?php
namespace App\Providers;

use Auth;
use Illuminate\Support\Facades\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Providers\EloquentCustomUserProvider;

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
        $this->registerPolicies($gate);

        // Binding eloquent.custom to our EloquentCustomUserProvider
        Auth::provider('eloquent.custom', function($app, array $config) {
            return new EloquentCustomUserProvider($app['hash'], $config['model']);
        });

    }
}