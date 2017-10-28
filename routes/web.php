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

	Route::get('/', 'DashboardController@index');
	
	Route::group(['middleware'=>'auth.admin'], function(){

		Route::group(['prefix'=>'panel/admin'], function(){
			Route::get('/', 'Administrador\CatalogoManagerController@index');
			Route::get('catalogos', 'Administrador\CatalogoManagerController@index');
			Route::get('catalogos/tipos-documentos', 'Administrador\CatalogoManagerController@postData');

		});

	});
	
});