<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Componentes\Modal\ModalBuilder;

class ModalServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
        $this->app->singleton(ModalBuilder::class, function ($app){
            return new ModalBuilder(config('modal'));
        });
    }
}