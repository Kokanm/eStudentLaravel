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

Route::get('/', function(){
    return View::make('home');
});

Route::get('find', function(){
    return View::make('findstudent');
});

Route::post('find', 'StudentController@search');

Route::get('vpis', 'VpisniListController@select');

Route::post('vpis', 'VpisniListController@vpisi');

Route::get('potrdi', 'PotrditevVpisaController@nepotrjeni');

Route::post('pregled/{vpisna}', 'IzpisVpisnegaListaController@pregled');

Route::post('potrdi/{vpisna}', 'PotrditevVpisaController@potrdi');

Route::post('izpis/{vpisna}', 'IzpisVpisnegaListaController@vpisnilist');