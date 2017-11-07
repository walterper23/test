<?php

DB::listen(function($query){
	//echo "<pre style=\"z-index:500\">{$query->sql}</pre>";
});

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
	
	Route::group(['prefix'=>'configuracion'], function(){
			
		Route::get('/', 'Dashboard/ConfiguracionController@index');
		
		Route::group(['prefix'=>'catalogos','namespace'=>'Configuracion'], function(){
			Route::get('/', 'Administrador\CatalogoManagerController@postData');
			// Catálogo de tipos de documentos
			Route::group(['prefix'=>'tipos-documentos','namespace'=>'Catalogo'],function(){
				Route::get('/',                'TipoDocumentoController@index');
				Route::post('post-data',       'TipoDocumentoController@postDataTable');
				Route::post('nuevo',           'TipoDocumentoController@formTipoDocumento');
				Route::post('post-nuevo',      'TipoDocumentoController@postNuevoTipoDocumento');
				Route::post('editar',          'TipoDocumentoController@editarTipoDocumento');
				Route::post('post-editar',     'TipoDocumentoController@postEditarTipoDocumento');
				Route::post('post-desactivar', 'TipoDocumentoController@postDesactivarTipoDocumento');
			});

			Route::group(['prefix'=>'direcciones','namespace'=>'Catalogo'],function(){
				Route::get('/',                'DireccionController@index');
				Route::post('post-data',       'DireccionController@postDataTable');
				Route::post('nuevo',           'DireccionController@formTipoDocumento');
				Route::post('post-nuevo',      'DireccionController@postNuevoTipoDocumento');
				Route::post('editar',          'DireccionController@editarTipoDocumento');
				Route::post('post-editar',     'DireccionController@postEditarTipoDocumento');
				Route::post('post-desactivar', 'DireccionController@postDesactivarTipoDocumento');
			});

			Route::group(['prefix'=>'departamentos','namespace'=>'Catalogo'],function(){
				Route::get('/',                'DepartamentoController@index');
				Route::post('post-data',       'DepartamentoController@postDataTable');
				Route::post('nuevo',           'DepartamentoController@formDepartamento');
				Route::post('post-nuevo',      'DepartamentoController@postNuevoDepartamento');
				Route::post('editar',          'DepartamentoController@editarDepartamento');
				Route::post('post-editar',     'DepartamentoController@postEditarDepartamento');
				Route::post('post-desactivar', 'DepartamentoController@postDesactivarDepartamento');
			});
			

		});

	});
	
});