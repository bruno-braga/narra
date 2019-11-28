<?php

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

Auth::routes();

Route::feeds();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/fd/{id}', 'HomeController@fd');



Route::resource('/episodes', 'EpisodesController');
Route::resource('/programs', 'ProgramsController');

Route::get('/programs/{program}/settings', 'ProgramsController@settings');

Route::post(
  '/programs/{program}/settings',
  'ProgramsController@storeSettings'
)
->name('storeSettings');

// Route::resource('/settings', 'SettingsController');
