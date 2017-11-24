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
					Route::post('post-nuevo',      'AnexoController@postNuevoAnexo');
					Route::post('editar',          'AnexoController@editarAnexo');
					Route::post('post-editar',     'AnexoController@postEditarAnexo');
					Route::post('post-desactivar', 'AnexoController@desactivarAnexo');
					Route::post('post-eliminar',   'AnexoController@eliminarAnexo');
				});
				
				// Catálogo de departamentos
				Route::group(['prefix'=>'departamentos'],function(){
					Route::get('/',                'DepartamentoController@index');
					Route::post('post-data',       'DepartamentoController@postDataTable');
					Route::post('nuevo',           'DepartamentoController@formDepartamento');
					Route::post('post-nuevo',      'DepartamentoController@postNuevoDepartamento');
					Route::post('editar',          'DepartamentoController@editarDepartamento');
					Route::post('post-editar',     'DepartamentoController@postEditarDepartamento');
					Route::post('post-desactivar', 'DepartamentoController@desactivarDepartamento');
					Route::post('post-eliminar',   'DepartamentoController@eliminarDepartamento');
				});
				
				// Catálogo de direcciones
				Route::group(['prefix'=>'direcciones'],function(){
					Route::get('/',                'DireccionController@index');
					Route::post('post-data',       'DireccionController@postDataTable');
					Route::post('nuevo',           'DireccionController@formDireccion');
					Route::post('post-nuevo',      'DireccionController@postNuevaDireccion');
					Route::post('editar',          'DireccionController@editarDireccion');
					Route::post('post-editar',     'DireccionController@postEditarDireccion');
					Route::post('post-desactivar', 'DireccionController@desactivarDireccion');
					Route::post('post-eliminar',   'DireccionController@eliminarDireccion');
				});

				// Catálogo de puestos
				Route::group(['prefix'=>'puestos'],function(){
					Route::get('/',                'PuestoController@index');
					Route::post('post-data',       'PuestoController@postDataTable');
					Route::post('nuevo',           'PuestoController@formPuesto');
					Route::post('post-nuevo',      'PuestoController@postNuevoPuesto');
					Route::post('editar',          'PuestoController@editarPuesto');
					Route::post('post-editar',     'PuestoController@postEditarPuesto');
					Route::post('post-desactivar', 'PuestoController@desactivarPuesto');
					Route::post('post-eliminar',   'PuestoController@eliminarPuesto');
				});

				// Catálogo de tipos de documentos
				Route::group(['prefix'=>'tipos-documentos'],function(){
					Route::get('/',                'TipoDocumentoController@index');
					Route::post('post-data',       'TipoDocumentoController@postDataTable');
					Route::post('nuevo',           'TipoDocumentoController@formTipoDocumento');
					Route::post('post-nuevo',      'TipoDocumentoController@postNuevoTipoDocumento');
					Route::post('editar',          'TipoDocumentoController@formEditarTipoDocumento');
					Route::post('post-editar',     'TipoDocumentoController@postEditarTipoDocumento');
					Route::post('post-desactivar', 'TipoDocumentoController@desactivarTipoDocumento');
					Route::post('post-eliminar',   'TipoDocumentoController@eliminarTipoDocumento');
				});
			});

			Route::group(['prefix'=>'usuarios','namespace'=>'Usuario'], function(){
				Route::get('/',                'UsuarioController@index');
				Route::get('nuevo',            'UsuarioController@formUsuario');
				Route::post('post-data',       'UsuarioController@postDataTable');
				Route::post('post-nuevo',      'UsuarioController@postNuevoUsuario');
				Route::post('editar',          'UsuarioController@editarUsuario');
				Route::post('post-editar',     'UsuarioController@postEditarUsuario');
				Route::post('post-desactivar', 'UsuarioController@desactivarUsuario');
				Route::post('post-eliminar',   'UsuarioController@eliminarUsuario');


				Route::group(['prefix'=>'roles'], function(){
					Route::get('/',                'RolController@index');
					Route::get('nuevo',            'RolController@formUsuario');
					Route::post('post-data',       'RolController@postDataTable');
					Route::post('post-nuevo',      'RolController@postNuevoUsuario');
					Route::post('editar',          'RolController@editarUsuario');
					Route::post('post-editar',     'RolController@postEditarUsuario');
					Route::post('post-desactivar', 'RolController@desactivarUsuario');
					Route::post('post-eliminar',   'RolController@eliminarUsuario');
				});

				Route::group(['prefix'=>'permisos'], function(){
					Route::get('/',                'PermisoController@index');
					Route::get('nuevo',            'PermisoController@formUsuario');
					Route::post('post-data',       'PermisoController@postDataTable');
					Route::post('post-nuevo',      'PermisoController@postNuevoUsuario');
					Route::post('editar',          'PermisoController@editarUsuario');
					Route::post('post-editar',     'PermisoController@postEditarUsuario');
					Route::post('post-desactivar', 'PermisoController@desactivarUsuario');
					Route::post('post-eliminar',   'PermisoController@eliminarUsuario');
				});

				
			});

		});
	
	});

});