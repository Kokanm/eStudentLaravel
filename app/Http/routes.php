<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('referent/uvoz_podatkov', ['middleware' => 'referent', 'uses' => 'ReferentController@izberi']);
Route::post('referent/uvoz_podatkov', ['middleware' => 'referent', 'uses' => 'ReferentController@uvozi']);

Route::get('find', function(){
    return View::make('findstudent');
});

Route::get('najdiprofesor', ['middleware' => 'skrbnik', 'uses' => 'NajdiIzvajalcaController@lista']);
Route::post('najdiprofesor', ['middleware' => 'skrbnik', 'uses' => 'NajdiIzvajalcaController@najdi']);
Route::post('najdiprofesor/{stevilo}', ['middleware' => 'skrbnik', 'uses' => 'NajdiIzvajalcaController@dodaj']);

Route::post('find', ['middleware' => 'referent', 'uses' => 'StudentController@search']);

Route::get('vpis', ['middleware' => 'student', 'uses' => 'VpisniListController@select']);

Route::post('vpis', ['middleware' => 'student', 'uses' => 'VpisniListController@vpisi']);

Route::get('potrdi', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@nepotrjeni']);

Route::post('pregled/{vpisna}', ['middleware' => 'referent', 'uses' => 'IzpisVpisnegaListaController@pregled']);

Route::post('potrdi/{vpisna}', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@potrdi']);

Route::post('natisni/{vpisna}', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@natisni']);

Route::post('izpis/{vpisna}', ['middleware' => 'referent', 'uses' => 'IzpisVpisnegaListaController@vpisnilist']);

Route::post('vpisi/{vpisna}', ['middleware' => 'student', 'uses' => 'IzbirniPredmetiController@izberi']);

Route::post('tisk/{vpisna}', ['middleware' => 'referent', 'uses' => 'TiskajController@izpis']);