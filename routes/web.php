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

//Logistics
Route::get('/logistics', 'LogisticsController@index')->middleware('auth');
Route::get('/logistics/create', 'LogisticsController@create')->middleware('auth');
Route::post('/logistics/create', 'LogisticsController@store')->middleware('auth');
Route::get('/logistics/{deliveryGroup}', 'LogisticsController@show')->middleware('auth');
Route::get('/logistics/{deliveryGroup}/edit', 'LogisticsController@edit')->middleware('auth');
Route::put('/logistics/{deliveryGroup}/edit', 'LogisticsController@update')->middleware('auth');
Route::get('/logistics/{deliveryGroup}/delete', 'LogisticsController@destroy')->middleware('auth');

//Inventory
Route::get('/inventory', 'InventoryController@index')->middleware('auth');
Route::get('/inventory/create', 'InventoryController@create')->middleware('auth');
Route::post('/inventory/create', 'InventoryController@store')->middleware('auth');
Route::get('/inventory/{inventoryItem}', 'InventoryController@show')->middleware('auth');
Route::get('/inventory/show/{inventoryItem}', 'InventoryController@show')->middleware('auth');
Route::get('/inventory/{inventoryItem}/edit', 'InventoryController@edit')->middleware('auth');
Route::put('/inventory/{inventoryItem}/edit', 'InventoryController@update')->middleware('auth');
Route::get('/inventory/{inventoryItem}/delete', 'InventoryController@destroy')->middleware('auth');