<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('config_var'))
{
    // Helper para recuperar las variables de configuración del sistema
    function config_var( $key_var, $default = '' )
    {
        $config = Cache::remember('SystemConfigVar',120,function(){
            return \App\Model\Sistema\MSistemaConfig::select('SYCO_VARIABLE','SYCO_VALOR') -> pluck('SYCO_VALOR','SYCO_VARIABLE') -> toArray();
        });

        return $config[$key_var];

    }
}

if (! function_exists('title'))
{
    // Helper para mostrar el título de la página
    function title( $title = '' )
    {
        return sprintf('%s :: %s',config_var('Sistema.Siglas'), $title);
    }
}

if (! function_exists('permisoUsuario'))
{
    // Helper para recuperar todos los permisos del usuario

    function permisoUsuario( $permiso )
    {
        $permisos = Cache::remember('PermisosUsuario',120,function(){
            return user() -> Permisos() -> pluck('SYPE_CODIGO') -> toArray();
        });

        return in_array($permiso, $permisos);
    }
}

if (! function_exists('user'))
{
    // Helper para recuperar al usuario en sesión
    function user()
    {
        return \Auth::user();
    }
}

if (! function_exists('userKey'))
{
    // Helper para recuperar el ID del usuario en sesión
    function userKey()
    {
        return user() -> getKey();
    }
}