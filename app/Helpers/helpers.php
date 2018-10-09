<?php
use Illuminate\Support\Facades\Cache;

if (! function_exists('config_var'))
{
    // Helper para recuperar las variables de configuración del sistema
    function config_var( $key_var, $default = '' )
    {
        $config = Cache::rememberForever('System.Config.Variables',function(){
            return \App\Model\System\MSystemConfig::select('SYCO_VARIABLE','SYCO_VALOR') -> pluck('SYCO_VALOR','SYCO_VARIABLE') -> toArray();
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
        $permisosUsuario = sprintf('Permisos.Usuario.Actual.%d', userKey());

        //Cache::flush('Permisos.Usuario.Actual');
        $permisos = Cache::rememberForever($permisosUsuario,function(){
            return user() -> Permisos;
        });
        

        $permisos = $permisos -> pluck('SYPE_CODIGO') -> toArray();

        //dd('a ber',$permisos,in_array($permiso, $permisos),$permiso);
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

if (! function_exists('userIsSuperAdmin'))
{
    // Helper para recuperar el ID del usuario en sesión
    function userIsSuperAdmin()
    {
        return user() -> isSuperAdmin();
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