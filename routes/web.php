<?php



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/home', function(){
    return redirect('/dashboard');
});

//eve sso routes


//EveLoginController
Route::get('/evelogin', 'EveLoginController@index');
Route::get('/evelogin/response','EveLoginController@create');
Route::get('/testlink', 'EveLoginController@show');

//DashboardController
Route::get('/dashboard', 'DashboardController@index')->middleware('auth');

//Characters
Route::get('/characters', 'Characters@index')->middleware('auth');
Route::get('/dropcharacter/{character_id}', 'Characters@destroy')->middleware('auth');
Route::get('/selectcharacter/{character_id}', 'Characters@store')->middleware('auth');

//Market Orders
Route::get('/marketorders', 'MarketOrdersController@index')->middleware('auth');