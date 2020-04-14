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
Route::get('/marketorders/{marketOrder}', 'MarketOrdersController@show')->middleware('auth');

//Logistics
Route::get('/logistics', 'LogisticsController@index')->middleware('auth');
Route::get('/logistics/create', 'LogisticsController@create')->middleware('auth');
Route::post('/logistics/create', 'LogisticsController@store')->middleware('auth');
Route::get('/logistics/{deliveryGroup}', 'LogisticsController@show')->middleware('auth');
Route::get('/logistics/{deliveryGroup}/edit', 'LogisticsController@edit')->middleware('auth');
Route::put('/logistics/{deliveryGroup}/edit', 'LogisticsController@update')->middleware('auth');
Route::get('/logistics/{deliveryGroup}/delete', 'LogisticsController@destroy')->middleware('auth');

//Inventory Server Partials
Route::get('inventory/itemsearch', 'InventoryController@itemSearch');

//Inventory
Route::get('/inventory', 'InventoryController@index')->middleware('auth');
Route::get('/inventory/create', 'InventoryController@create')->middleware('auth');
Route::post('/inventory/create', 'InventoryController@store')->middleware('auth');
Route::get('/inventory/{inventoryItem}', 'InventoryController@show')->middleware('auth');
Route::get('/inventory/show/{inventoryItem}', 'InventoryController@show')->middleware('auth');
Route::get('/inventory/{inventoryItem}/edit', 'InventoryController@edit')->middleware('auth');
Route::put('/inventory/{inventoryItem}/edit', 'InventoryController@update')->middleware('auth');
Route::get('/inventory/{inventoryItem}/remove', 'InventoryController@remove')->middleware('auth');
Route::get('/inventory/{inventoryItemID}/add/{logisticsGroupID}', 'InventoryController@add')->middleware('auth');
Route::get('/inventory/{inventoryItem}/delete', 'InventoryController@destroy')->middleware('auth');

//Shopping List
Route::get('/shoppinglist', 'ShoppingListController@index')->middleware('auth');
Route::get('/shoppinglist/create', 'ShoppingListController@create')->middleware('auth');
Route::post('/shoppinglist/create', 'ShoppingListController@store')->middleware('auth');
Route::get('/shoppinglist/{shoppingList}', 'ShoppingListController@show')->middleware('auth');
Route::get('/shoppinglist/{shoppingList}/edit', 'ShoppingListController@edit')->middleware('auth');
Route::put('/shoppinglist/{shoppingList}/edit', 'ShoppingListController@update')->middleware('auth');
Route::get('/shoppinglist/{shoppingList}/delete', 'ShoppingListController@destroy')->middleware('auth');


//Shopping List Items
Route::post('/shoppinglistitem/create/{shoppingListID}', 'ShoppingListItemController@store')->middleware('auth');
Route::get('/shoppinglistitem/{shoppingListItem}', 'ShoppingListItemController@show')->middleware('auth');
Route::get('/shoppinglistitem/{shoppingListItem}/edit', 'ShoppingListItemController@edit')->middleware('auth');
Route::put('/shoppinglistitem/{shoppingListItem}/edit', 'ShoppingListItemController@update')->middleware('auth');
Route::get('/shoppinglistitem/{shoppingListItem}/delete', 'ShoppingListItemController@destroy')->middleware('auth');


//Transactions Partials
Route::get('/transactions/search', 'TransactionsController@search')->middleware('auth');

//Transactions
Route::get('/transactions', 'TransactionsController@index')->middleware('auth');
Route::get('/transactions/search/show', 'TransactionsController@searchShow')->middleware('auth');
Route::get('/transactions/search/sell', 'TransactionsController@searchSell')->middleware('auth');
Route::get('/transactions/search/buy', 'TransactionsController@searchBuy')->middleware('auth');
