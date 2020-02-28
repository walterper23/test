<?php
Route::middleware(['preventBackHistory','queryListenLog'])->group(function(){

    Route::get('login',  'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('password/reset',         'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email',        'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset',        'Auth\ResetPasswordController@reset');
    Route::middleware('auth')->group(function(){
        // Perfil del usuario y configuración de preferencias
        Route::prefix('/')->namespace('Dashboard')->group(function(){
            // Dashboard del usuario. Para las notificaciones principalmente.
            Route::get('/',        'DashboardController@index');
            Route::post('manager', 'DashboardController@manager');
        });

        /*************************** IMJUVE ROUTES *************************************************/
        Route::prefix('imjuve')->group(function(){

            Route::prefix('instituto')->namespace('imjuve\Instituto')->group(function(){
                Route::get('/',        'InstitutoController@index');
                Route::post('nuevo',    'InstitutoController@formNuevoInstituto');
                Route::post('manager',  'InstitutoController@manager');

            });


            Route::prefix('afiliacion')->namespace('imjuve\Afiliacion')->group(function(){
                Route::get('/',        'AfiliacionController@index');
                Route::post('nuevo',    'AfiliacionController@formNuevaAfiliacion');
                Route::post('editar',          'AfiliacionController@formEditarAfiliacion');
                Route::post('manager',  'AfiliacionController@manager');
                Route::post('post-data',       'AfiliacionController@postDataTable');


            });


            

            Route::prefix('catalogos')->namespace('imjuve\Afiliacion')->group(function(){
                Route::get('/',        'AfiliacionController@index');
                Route::prefix('local')->group(function(){
                    Route::get('/',                 'DocumentoController@local');
                    Route::post('anexos',           'DocumentoController@localAnexos');
                    Route::post('escaneos',         'DocumentoController@localEscaneos');
                    Route::get('escaneos',          'DocumentoController@localArchivoEscaneo');
                    Route::post('anexos-escaneos',  'DocumentoController@localAnexosEscaneos');
                });
            });

            Route::prefix('utils')->namespace('imjuve\Utils')->group(function(){
                Route::post('municipios',    'UtilController@getMunicipios');
                Route::post('localidades',  'UtilController@getLocalidades');
                Route::post('asentamientos',  'UtilController@getAsentamientos');

            });
        });
        /*************************** END IMJUVE ROUTES *********************************************/
    });
});

Route::get('correo', 'AppMovil\AppMovilController@correo');
Route::get('grafica/{tipo}/{fecha1}/{fecha2}', 'AppMovil\AppMovilController@Grafica');
Route::get('token', 'AppMovil\AppMovilController@token');
Route::get('loginApp/{usuario}/{pass}','AppMovil\AppMovilController@login');
Route::get('appCreateId/{id}', 'AppMovil\AppMovilController@IdAPP');
Route::get('alerta', 'AppMovil\AppMovilController@notificacion');
Route::get('id/{usuario}', 'AppMovil\AppMovilController@idUser');
Route::get('update_doc/{id_doc}/{id_user}', 'AppMovil\AppMovilController@updateLeidos');