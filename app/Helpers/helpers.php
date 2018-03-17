<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('config_var')) {
    /**
     * Recuperar las variables de configuración del sistema
     *
     * @param  array  $array
     * @return array
     */
    function config_var( $key_var, $default = '' )
    {

        Cache::flush();

        $config = Cache::remember('SystemConfigVar',120,function(){
            return \App\Model\Sistema\MSistemaConfig::select('SYCO_VARIABLE','SYCO_VALOR') -> pluck('SYCO_VALOR','SYCO_VARIABLE') -> toArray();
        });

        return $config[$key_var];

    }
}

if (! function_exists('title')) {
    /**
     * Recuperar las variables de configuración del sistema
     *
     * @param  array  $array
     * @return array
     */
    function title( $title = '' )
    {
        return sprintf('%s :: %s',config_var('Sistema.Siglas'), $title);
    }
}