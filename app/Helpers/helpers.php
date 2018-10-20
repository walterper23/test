<?php

if (! function_exists('config_var'))
{
    // Helper para recuperar las variables de configuración del sistema
    function config_var( $key_var, $default = null )
    {
        $config = cache('System.Config.Variables.Array');

        if( isset($config[$key_var]) )
        {
            return $config[$key_var];
        }

        return $default;
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
        $permisosUsuario = sprintf('Permisos.Usuario.Actual.%d', userKey());

        $permisos = cache()->rememberForever($permisosUsuario,function(){
            return user()->Permisos;
        });

        $permisos = $permisos->pluck('SYPE_CODIGO')->toArray();

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
        return \Auth::id();
    }
}

if (! function_exists('nombreFecha'))
{
    // Helper para obtener el nombre del mes
    function nombreFecha(String $fecha)
    {
        $meses = [
            'x01' => 'Enero',
            'x02' => 'Febrero',
            'x03' => 'Marzo',
            'x04' => 'Abril',
            'x05' => 'Mayo',
            'x06' => 'Junio',
            'x07' => 'Julio',
            'x08' => 'Agosto',
            'x09' => 'Septiembre',
            'x10' => 'Octubre',
            'x11' => 'Noviembre',
            'x12' => 'Diciembre',
        ];

        foreach ($meses as $key => $value) {
            $fecha = str_replace($key, $value, $fecha);
        }

        return $fecha;
    }
}