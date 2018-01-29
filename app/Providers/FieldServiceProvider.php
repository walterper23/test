<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Componentes\Field\FieldBuilder;

class FieldServiceProvider extends ServiceProvider {
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
        $this->app->singleton(FieldBuilder::class, function ($app){
            return new FieldBuilder(config('field'));
        });
    }
}