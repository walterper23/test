<?php

DB::listen(function($query){
    \Illuminate\Support\Facades\Log::info($query -> sql);
    \Illuminate\Support\Facades\Log::info($query -> bindings);
});

Route::middleware('preventBackHistory') -> group(function(){

    Route::get('documentacion', 'DocumentacionController@index');

    Route::get('login',  'Auth\LoginController@showLoginForm') -> name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout') -> name('logout');

    // Password Reset Routes...
    Route::get('password/reset',         'Auth\ForgotPasswordController@showLinkRequestForm') -> name('password.request');
    Route::post('password/email',        'Auth\ForgotPasswordController@sendResetLinkEmail') -> name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm') -> name('password.reset');
    Route::post('password/reset',        'Auth\ResetPasswordController@reset');

    Route::middleware('auth') -> group(function(){

        // Perfil del usuario y configuración de preferencias
        Route::prefix('/') -> namespace('Dashboard') -> group(function(){

            // Dashboard del usuario. Para las notificaciones principalmente.
            Route::get('/',        'DashboardController@index');
            Route::post('manager', 'DashboardController@manager');

            // Peticiones extras
            Route::prefix('extra-request') -> group(function(){
                
            });

            Route::prefix('usuario') -> group(function(){
                Route::prefix('perfil') -> group(function(){
                    Route::get('/',        'PerfilController@index');
                    Route::post('manager', 'PerfilController@manager');
                });
                /*Route::prefix('preferencias') -> group(function(){
                    Route::get('/', 'PreferenciasController@index');
                });*/
            });

        });

        // Rutas libres de permiso para visualizar los documentos, sus anexos y sus escaneos
        Route::prefix('documento') -> namespace('Documento') -> group(function(){

            Route::prefix('local') -> group(function(){
                Route::get('/',                 'DocumentoController@local');
                Route::post('anexos',           'DocumentoController@localAnexos');
                Route::post('escaneos',         'DocumentoController@localEscaneos');
                Route::get('escaneos',          'DocumentoController@localArchivoEscaneo');
                Route::post('anexos-escaneos',  'DocumentoController@localAnexosEscaneos');
            });
            
            Route::prefix('foraneo') -> group(function(){
                Route::get('/',                 'DocumentoController@foraneo');
                Route::post('anexos',           'DocumentoController@foraneoAnexos');
                Route::post('escaneos',         'DocumentoController@foraneoEscaneos');
                Route::get('escaneos',          'DocumentoController@foraneoArchivoEscaneo');
                Route::post('anexos-escaneos',  'DocumentoController@foraneoAnexosEscaneos');
            });

        });

        // Recepcion de documentos locales y foráneos
        Route::prefix('recepcion') -> namespace('Recepcion') -> group(function(){
            
            Route::get('acuse/documento/{acuse}', 'AcuseRecepcionController@index');
            
            Route::redirect('/',          '/recepcion/documentos/recepcionados?view=denuncias');
            Route::redirect('documentos', '/recepcion/documentos/recepcionados?view=denuncias');
            
            // Recepción de documentos locales
            Route::prefix('documentos') -> middleware('can:REC.DOCUMENTO.LOCAL') -> group(function(){

                Route::get('recepcionados',    'RecepcionController@index');
                Route::post('post-data',       'RecepcionController@postDataTable');
                Route::get('nueva-recepcion',  'RecepcionController@formNuevaRecepcion');
                Route::post('manager',         'RecepcionController@manager');
                Route::get('en-captura',       'RecepcionController@documentosEnCaptura');

                Route::prefix('foraneos') -> group(function(){
                    Route::get('/',                'RecibirRecepcionForaneaController@index');
                    Route::post('post-data',       'RecibirRecepcionForaneaController@postDataTable');
                    Route::get('nueva-recepcion',  'RecibirRecepcionForaneaController@formNuevaRecepcion');
                    Route::post('manager',         'RecibirRecepcionForaneaController@manager');
                    Route::get('en-captura',       'RecibirRecepcionForaneaController@documentosEnCaptura');
                });

            });

            // Recepción de documentos foráneos
            Route::prefix('documentos-foraneos') -> group(function(){
                Route::get('/',                'RecepcionForaneaController@index');
                Route::get('recepcionados',    'RecepcionForaneaController@index');
                Route::post('post-data',       'RecepcionForaneaController@postDataTable');
                Route::get('nueva-recepcion',  'RecepcionForaneaController@formNuevaRecepcion');
                Route::post('manager',         'RecepcionForaneaController@manager');
                Route::get('en-captura',       'RecepcionForaneaController@documentosEnCaptura');
            });

        });

        // Panel de trabajo del personal de las direcciones y departamentos
        Route::redirect('panel', 'panel/documentos?view=all');
        Route::prefix('panel') -> namespace('Panel') -> group(function(){
            
            Route::prefix('documentos') -> group(function(){

                Route::get('/',                       'PanelController@index');
                Route::get('foraneos',                'PanelController@verRecepcionesForaneas');
                Route::post('anexos-escaneos',        'PanelController@verAnexosEscaneos');
                Route::post('cambio-estado',          'PanelController@formCambioEstadoDocumento');
                Route::post('editar-cambio-estado',   'PanelController@formEditarCambioEstadoDocumento');
                Route::post('no-expediente-denuncia', 'PanelController@formAsignarNoExpedienteDenuncia');
                Route::post('manager',                'PanelController@manager');
                
                // Seguimiento de los documentos
                Route::prefix('seguimiento') -> group(function(){
                    Route::get('/',      'SeguimientoController@index');
                });

                // Documentos semaforizados
                Route::prefix('semaforizados') -> middleware('can:SEG.ADMIN.SEMAFORO') -> group(function(){
                    Route::get('/',                'DocumentoSemaforizadoController@index');
                    Route::post('post-data',       'DocumentoSemaforizadoController@postDataTable');
                    Route::post('seguimiento',     'DocumentoSemaforizadoController@verSeguimiento');
                });
            });

        });
        
        // Configuración de catálogos, usuarios y sistema
        Route::redirect('configuracion','configuracion/catalogos');
        Route::prefix('configuracion') -> namespace('Configuracion') -> group(function(){
            
            Route::prefix('catalogos') -> namespace('Catalogo') -> group(function(){
                
                Route::get('/', 'CatalogoManagerController@index');

                // Catálogo de anexos
                Route::prefix('anexos') -> middleware('can:SIS.ADMIN.ANEXOS') -> group(function(){
                    Route::get('/',                'AnexoController@index');
                    Route::post('post-data',       'AnexoController@postDataTable');
                    Route::post('nuevo',           'AnexoController@formNuevoAnexo');
                    Route::post('editar',          'AnexoController@formEditarAnexo');
                    Route::post('manager',         'AnexoController@manager');
                });
                
                // Catálogo de departamentos
                Route::prefix('departamentos') -> middleware('can:SIS.ADMIN.DEPTOS') -> group(function(){
                    Route::get('/',          'DepartamentoController@index');
                    Route::post('post-data', 'DepartamentoController@postDataTable');
                    Route::post('nuevo',     'DepartamentoController@formNuevoDepartamento');
                    Route::post('editar',    'DepartamentoController@formEditarDepartamento');
                    Route::post('manager',   'DepartamentoController@manager');
                });
                
                // Catálogo de direcciones
                Route::prefix('direcciones') -> middleware('can:SIS.ADMIN.DIRECC') -> group(function(){
                    Route::get('/',          'DireccionController@index');
                    Route::post('post-data', 'DireccionController@postDataTable');
                    Route::post('nuevo',     'DireccionController@formNuevaDireccion');
                    Route::post('editar',    'DireccionController@formEditarDireccion');
                    Route::post('manager',   'DireccionController@manager');
                });

                // Catálogo de puestos
                Route::prefix('puestos') -> middleware('can:SIS.ADMIN.PUESTOS') -> group(function(){
                    Route::get('/',          'PuestoController@index');
                    Route::post('post-data', 'PuestoController@postDataTable');
                    Route::post('nuevo',     'PuestoController@formNuevoPuesto');
                    Route::post('editar',    'PuestoController@formEditarPuesto');
                    Route::post('manager',   'PuestoController@manager');
                });

                // Catálogo de estados de documentos
                Route::prefix('estados-documentos') -> middleware('can:SIS.ADMIN.ESTA.DOC') -> group(function(){
                    Route::get('/',          'EstadoDocumentoController@index');
                    Route::post('post-data', 'EstadoDocumentoController@postDataTable');
                    Route::post('nuevo',     'EstadoDocumentoController@formNuevoEstadoDocumento');
                    Route::post('editar',    'EstadoDocumentoController@formEditarEstadoDocumento');
                    Route::post('manager',   'EstadoDocumentoController@manager');
                });

            });

            // Administración de usuarios, sus permisos y asignaciones
            Route::prefix('usuarios') -> middleware('can:USU.ADMIN.USUARIOS') -> namespace('Usuario') -> group(function(){
                Route::get('/',           'UsuarioController@index');
                Route::post('post-data',  'UsuarioController@postDataTable');
                Route::post('nuevo',      'UsuarioController@formNuevoUsuario');
                Route::post('editar',     'UsuarioController@formEditarUsuario');
                Route::post('password',   'UsuarioController@formPassword');
                Route::post('manager',    'UsuarioController@manager');
            });

            Route::prefix('usuarios/permisos-asignaciones') -> middleware('can:USU.ADMIN.PERMISOS.ASIG') -> namespace('Usuario') -> group(function(){
                Route::get('/',           'PermisoAsignacionController@index');
                Route::post('post-data',  'PermisoAsignacionController@postDataTable');
                Route::get('nuevo',       'PermisoAsignacionController@formUsuario');
                Route::get('editar',      'PermisoAsignacionController@editarUsuario');
                Route::post('manager',    'PermisoAsignacionController@manager');
            });

            // Administración de las configuraciones del sistema
            Route::prefix('sistema') -> middleware('can:SIS.ADMIN.CONFIG') -> namespace('System') -> group(function(){
                
                // Administración del catálogo de los tipos de documentos del sistema
                Route::prefix('tipos-documentos') -> group(function(){
                    Route::get('/',          'SystemTipoDocumentoController@index');
                    Route::post('post-data', 'SystemTipoDocumentoController@postDataTable');
                    Route::post('nuevo',     'SystemTipoDocumentoController@formNuevoTipoDocumento');
                    Route::post('editar',    'SystemTipoDocumentoController@formEditarTipoDocumento');
                    Route::post('manager',   'SystemTipoDocumentoController@manager');
                });

                // Administración del catálogo de estados de documentos del sistema
                Route::prefix('estados-documentos') -> group(function(){
                    Route::get('/',          'SystemEstadoDocumentoController@index');
                    Route::post('post-data', 'SystemEstadoDocumentoController@postDataTable');
                    Route::post('editar',    'SystemEstadoDocumentoController@formEditarEstadoDocumento');
                    Route::post('manager',   'SystemEstadoDocumentoController@manager');
                });

                // Configuración de las variables del sistema
                Route::prefix('variables') -> group(function(){
                    Route::get('/',          'SystemVariableController@index');
                    Route::post('manager',   'SystemVariableController@manager');
                });

                // Administración de la bitácora del sistema
                Route::prefix('bitacora') -> group(function(){
                    Route::get('/',          'SystemBitacoraController@index');
                    Route::post('manager',   'SystemBitacoraController@manager');

                });

            });

        });

    });

});