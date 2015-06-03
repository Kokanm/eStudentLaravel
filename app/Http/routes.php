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

Route::post('find', ['middleware' => 'RefUci', 'uses' => 'StudentController@search']);

Route::post('student/{vpisna}', ['middleware' => 'ucitelj', 'uses' => 'PodatkiOStudentController@izpisStudent']);

Route::get('vpisa', ['middleware' => 'referent', 'uses' => 'VpisniListReferentController@nevpisani']);
Route::post('vpisa/{vpisna}', ['middleware' => 'referent', 'uses' => 'VpisniListReferentController@select']);
Route::post('vpisaK/{vpisna}', ['middleware' => 'referent', 'uses' => 'VpisniListReferentController@vpisi']);

Route::get('vpis', ['middleware' => 'student', 'uses' => 'VpisniListController@select']);
Route::post('vpis', ['middleware' => 'student', 'uses' => 'VpisniListController@vpisi']);

Route::get('potrdi', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@nepotrjeni']);
Route::post('potrdi/{vpisna}', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@potrdi']);
Route::post('natisni/{vpisna}', ['middleware' => 'referent', 'uses' => 'PotrditevVpisaController@natisni']);

Route::post('pregled/{vpisna}', ['middleware' => 'referent', 'uses' => 'IzpisVpisnegaListaController@pregled']);

Route::post('izpis/{vpisna}', ['middleware' => 'referent', 'uses' => 'IzpisVpisnegaListaController@vpisnilist']);

Route::post('vpisi/{vpisna}', ['middleware' => 'student', 'uses' => 'IzbirniPredmetiController@izberi']);
Route::post('vpisiK/{vpisna}', ['middleware' => 'referent', 'uses' => 'IzbirniPredmetiController@izberi2']);

Route::post('tisk/{vpisna}', ['middleware' => 'referent', 'uses' => 'TiskajController@izpisReferent']);
Route::get('tisks', ['middleware' => 'student', 'uses' => 'IzpisVpisnegaListaController@izpisStudent']);

Route::post('zeton/{vpisna}', ['middleware' => 'referent', 'uses' => 'ReferentController@dodajZeton']);

Route::get('prijava', ['middleware' => 'student', 'uses' => 'PrijavaNaIzpitController@Roki']);
Route::post('prijava/{vse}', ['middleware' => 'StudRef', 'uses' => 'PrijavaNaIzpitController@Prijava']);
Route::post('odjava/{vse}', ['middleware' => 'StudRef', 'uses' => 'PrijavaNaIzpitController@Odjava']);
Route::post('prijavaodjava/{vse}', ['middleware' => 'referent', 'uses' => 'PrijavaNaIzpitController@RokiR']);

Route::get('kartotecniS', ['middleware' => 'student', 'uses' => 'KartotecniListController@vrniVsa']);
Route::post('kartotecniS', ['middleware' => 'student', 'uses' => 'KartotecniListController@gumb']);


Route::post('seznamPredmetIzvoz', ['middleware' => 'RefUci', 'uses' => 'IzvozController@studentiPredmetaPdf']);
Route::get('seznamPredmet', ['middleware' => 'RefUci', 'uses' => 'SeznamStudentovPredmetaController@izbiraPrograma']);
Route::post('seznamPredmet', ['middleware' => 'RefUci', 'uses' => 'SeznamStudentovPredmetaController@izbiraPredmeta']);
Route::post('seznamPredmet2', ['middleware' => 'RefUci', 'uses' => 'SeznamStudentovPredmetaController@studentiPredmeta']);

Route::get('izpitniroki', ['middleware' => 'referent', 'uses' => 'IzpitniRokiController@izberiStudijskiProgramInLetnik']);
Route::post('izpitnirokiurejanje', ['middleware' => 'referent', 'uses' => 'IzpitniRokiController@urejanjeIzpitnihRokov']);
Route::get('izpitnirokiprofesor', ['middleware' => 'ucitelj', 'uses' => 'IzpitniRokiController@izberiStudijskiProgramInLetnikProfesor']);
Route::post('izpitnirokiurejanjeprofesor', ['middleware' => 'ucitelj', 'uses' => 'IzpitniRokiController@urejanjeIzpitnihRokovProfesor']);

Route::get('rezultati', ['middleware' => 'RefUci', 'uses' => 'IzpisiRezultatiController@izberi1']);
Route::post('rezultati', ['middleware' => 'referent', 'uses' => 'IzpisiRezultatiController@izberi2']);
Route::post('rezultati/{predmet}', ['middleware' => 'RefUci', 'uses' => 'IzpisiRezultatiController@izberi3']);
Route::post('rezultati/{predmet}/{datum}', ['middleware' => 'RefUci', 'uses' => 'IzpisiRezultatiController@izpisi']);

Route::post('rezultatip', ['middleware' => 'ucitelj', 'uses' => 'IzpisiRezultatiController@izberiProf']);
