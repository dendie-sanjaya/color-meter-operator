<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'DashboardController@index')->name('dashboard');
Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::post('scan', 'DashboardController@scan')->name('dashboard');
Route::get('config', 'ConfigController@index')->name('config');
Route::post('colorList/saveAjax', 'ColorListController@saveAjax')->name('colorList'); 
Route::get('splashScreen', 'SplashScreenController@index')->name('splashScreen');
Route::get('colorScan', 'DashboardController@color_scan')->name('dashboard');


/*
Route::group(['middleware' => 'check.auth'], function() {
	//Route::get('dashboard', 'DashboardController@index');
});
*/
	
