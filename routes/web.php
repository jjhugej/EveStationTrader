<?php



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//eve sso routes

//index method re-routes to eve's login for first step of access token
Route::get('/evelogin', 'EveLoginController@index');
Route::get('/evelogin/response','EveLoginController@create');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/testlink', 'EveLoginController@show');
Route::get('/dropcharacter/{character_id}', 'Characters@destroy');

