<?php



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//eve sso routes


//EveLoginController
Route::get('/evelogin', 'EveLoginController@index');
Route::get('/evelogin/response','EveLoginController@create');
Route::get('/testlink', 'EveLoginController@show');

//DashboardController
Route::get('/dashboard', 'DashboardController@index');

//Characters
Route::get('/dropcharacter/{character_id}', 'Characters@destroy');
Route::get('/selectcharacter/{character_id}', 'Characters@store');

