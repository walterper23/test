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
	
	Route::group(['prefix'=>'panel/admin','middleware'=>'auth.admin'], function(){

			
		Route::get('/', 'Dashboard/DashboardAdministradorController@index');
		
		Route::group(['prefix'=>'catalogos'], function(){
			
			Route::get('/', 'Administrador\CatalogoManagerController@postData');

			// Catálogo de tipos de documentos
			Route::group(['prefix'=>'tipos-documentos','namespace'=>'Catalogo'],function(){
				Route::get('/',           'TipoDocumentoController@index');
				Route::post('datatables', 'TipoDocumentoController@postDataTable');
				Route::get('nuevo',       'TipoDocumentoController@index');
				Route::post('agregar',    'TipoDocumentoController@index');
			});
			

		});

	});
	
});