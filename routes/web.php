<?php

DB::listen(function($query){
	//echo "<pre style=\"z-index:500\">{$query->sql}</pre>";
});

Route::group(['middleware' => 'preventBackHistory'],function(){

	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login');
	Route::get('logout', 'Auth\LoginController@logout')->name('logout');

	// Password Reset Routes...
	Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

	Route::group(['middleware'=>'auth'], function(){

		Route::get('/', 'Dashboard\DashboardController@index');

		Route::group(['prefix'=>'perfil','namespace'=>'Dashboard'], function(){
			Route::get('/', 'PerfilController@index');
		});

		Route::group(['prefix'=>'recepcion'], function(){
			Route::get('/',  'RecepcionController@index');
			Route::group(['prefix'=>'documentos'], function(){
				Route::get('/',          'RecepcionController@index');
				Route::post('post-data', 'RecepcionController@postDataTable');
				Route::get('nuevo',      'RecepcionController@nuevaRecepcion');
			});
		});	
		
		Route::group(['prefix'=>'configuracion','namespace'=>'Configuracion'], function(){
			Route::get('/', 'ConfiguracionController@index');
			Route::group(['prefix'=>'catalogos','namespace'=>'Catalogo'], function(){
				Route::get('/', 'CatalogoManagerController@index');

				// Catálogo de anexos
				Route::group(['prefix'=>'anexos'],function(){
					Route::get('/',                'AnexoController@index');
					Route::post('post-data',       'AnexoController@postDataTable');
					Route::post('nuevo',           'AnexoController@formAnexo');
					Route::post('editar',          'AnexoController@editarAnexo');
					Route::post('manager',         'AnexoController@manager');
				});
				
				// Catálogo de departamentos
				Route::group(['prefix'=>'departamentos'],function(){
					Route::get('/',          'DepartamentoController@index');
					Route::post('post-data', 'DepartamentoController@postDataTable');
					Route::post('nuevo',     'DepartamentoController@formDepartamento');
					Route::post('editar',    'DepartamentoController@editarDepartamento');
					Route::post('manager',   'DepartamentoController@manager');
				});
				
				// Catálogo de direcciones
				Route::group(['prefix'=>'direcciones'],function(){
					Route::get('/',          'DireccionController@index');
					Route::post('post-data', 'DireccionController@postDataTable');
					Route::post('nuevo',     'DireccionController@formDireccion');
					Route::post('editar',    'DireccionController@editarDireccion');
					Route::post('manager',   'DireccionController@manager');
				});

				// Catálogo de puestos
				Route::group(['prefix'=>'puestos'],function(){
					Route::get('/',          'PuestoController@index');
					Route::post('post-data', 'PuestoController@postDataTable');
					Route::post('nuevo',     'PuestoController@formPuesto');
					Route::post('editar',    'PuestoController@editarPuesto');
					Route::post('manager',   'PuestoController@manager');
				});

				// Catálogo de tipos de documentos
				Route::group(['prefix'=>'tipos-documentos'],function(){
					Route::get('/',          'TipoDocumentoController@index');
					Route::post('post-data', 'TipoDocumentoController@postDataTable');
					Route::post('nuevo',     'TipoDocumentoController@formTipoDocumento');
					Route::post('editar',    'TipoDocumentoController@formEditarTipoDocumento');
					Route::post('manager',   'TipoDocumentoController@manager');
				});
			});

			Route::group(['prefix'=>'usuarios','namespace'=>'Usuario'], function(){
				Route::get('/',           'UsuarioController@index');
				Route::post('post-data',  'UsuarioController@postDataTable');
				Route::get('nuevo',       'UsuarioController@formUsuario');
				Route::get('editar',      'UsuarioController@editarUsuario');
				Route::post('manager',    'UsuarioController@manager');

				Route::group(['prefix'=>'roles'], function(){
					Route::get('/',           'RolController@index');
					Route::post('post-data',  'RolController@postDataTable');
					Route::get('nuevo',       'RolController@formRol');
					Route::get('editar',      'RolController@editarRol');
					Route::post('manager',    'RolController@manager');
				});

				Route::group(['prefix'=>'permisos'], function(){
					Route::get('/',           'PermisoController@index');
					Route::post('post-data',  'PermisoController@postDataTable');
					Route::get('nuevo',       'PermisoController@formUsuario');
					Route::get('editar',      'PermisoController@editarUsuario');
					Route::post('manager',    'PermisoController@manager');
				});

				
			});

		});
	
	});

});