<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * La clave sirve
 * parar
 */
class DocumentacionController extends Controller
{
    /**
     * De acuerdo
     * a los valores
     */
    public function index()
    {
        /*
        foreach ($folders as $folder) {
            foreach (scandir($folder) as $file) {
                if( $file != '.' && $file != '..' ){

                    
                    
                    $path_file = $folder . '/' .  $file;

                    echo $class_name;
                    echo '<br>';
                
                    $source = file_get_contents( $path_file );

                    $namespace = $this -> extract_namespace($source);

                    echo $namespace;
                    echo '<br>';

                    //echo $file;
                    
                    $tokens = token_get_all( $source );
                    $comment = array(
                        //T_COMMENT,      // All comments since PHP5 => //
                        //T_ML_COMMENT,   // Multiline comments PHP4 only
                        T_DOC_COMMENT   // PHPDoc comments   => /+ +/
                    );

                    foreach( $tokens as $token ) {
                        if( !in_array($token[0], $comment) )
                            continue;
                        
                        // Do something with the comment
                        $txt = $token[1];
                        echo $txt;
                        echo '<br>';
                    }
                }
            }
        }
        */


        echo '<style> * { font-family:Calibri; } </style>';

        $folders_namespaces = [
            //__DIR__ . '/Auth' => 'App\Http\Controllers\Auth',
            //__DIR__ . '/Configuracion/Catalogo' => 'App\Http\Controllers\Configuracion\Catalogo',
            __DIR__ . '/Configuracion/System' => 'App\Http\Controllers\Configuracion\System',
            __DIR__ . '/Configuracion/Usuario' => 'App\Http\Controllers\Configuracion\Usuario',
            //__DIR__ . '/Dashboard' => 'App\Http\Controllers\Dashboard',
            //__DIR__ . '/Documento' => 'App\Http\Controllers\Documento',
            //__DIR__ . '/Panel' => 'App\Http\Controllers\Panel',
            //__DIR__ . '/Recepcion' => 'App\Http\Controllers\Recepcion',

        ];

        foreach ($folders_namespaces as $folder => $namespace) {               // (2)
            foreach (scandir($folder) as $file) {                              // (4) (3)
                echo '<table border="1" cellspacing=0>';
                if( $file != '.' && $file != '..' ){

                    $class_name = substr($file, 0, -4);                        // (1)
                    $class = new \ReflectionClass( $namespace .'\\'. $class_name);


                    echo '<tr><td>Nombre de la clase</td><td>' . $class_name . '</td></tr>';
                    echo '<tr><td>Namespace</td><td>' . $namespace . '</td></tr>';
                    echo '<tr><td>Nombre del fichero</td><td>' . $file . '</td></tr>';
                    echo '<tr><td>Ubicación del fichero</td><td>' . str_replace('\\', '/', substr($folder,24)) . '</td></tr>';
                    echo '<tr><td>Descripción</td><td>'. str_replace(['/**',' * ',' */'], ['','',''], $class -> getDocComment()) . '</td></tr>';

                    echo '<tr><td colspan="2"><center>Métodos de la clase</td></td></tr>';

                    $methods = $class -> getMethods();
                    foreach ($methods as $method) {
                        echo '<tr><td>';
                        echo implode(' ', \Reflection::getModifierNames($method->getModifiers())).'<br>';
                        echo $method -> getName() . '()<br>';
                        echo 'Línea ' . $method -> getStartLine() .' a ' . $method -> getEndLine() . '<br>';
                        echo '</td>';
                        //echo '<tr><td></td><td>' . $method -> getStartLine() . '</td></tr>';
                        //echo '<tr><td></td><td>' . $method -> getEndLine() . '</td></tr>';
                        echo '<td>' . str_replace(['/**',' * ',' */'], ['','',''], $method -> getDocComment() ) . '</td></tr>';
                    }
                    
                }
                echo '</table><br><br>';
            }
        }

    }

}