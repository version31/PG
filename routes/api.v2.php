<?php


/*
|--------------------------------------------------------------------------
| Login and Register
|--------------------------------------------------------------------------
|
*/
Route::post('auth/sms/mobile', 'UserController@getMobile'); #step1
Route::post('auth/sms/code', 'UserController@getCode'); // #step2 or Login or Register
Route::post('auth/register', 'UserController@registerWithCode'); #step3



/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
|
*/
Route::get('/users/current/show-status', 'UserController@showStatus');



/*
|--------------------------------------------------------------------------
| Provider
|--------------------------------------------------------------------------
|
*/
Route::get('providers', 'ProviderController@index'); #7



Route::resource('products', 'ProductController')->only(['show','index']);



/*
|--------------------------------------------------------------------------
| Wallet
|--------------------------------------------------------------------------
|
*/
Route::post('/wallet/transfer', 'WalletController@transfer');
Route::post('/wallet/money-request', 'WalletController@moneyRequest');
Route::get('/wallet/transactions', 'WalletController@transactions');
Route::get('/wallet/buy-plans/{planId}', 'PaymentPlanController@payment');


//payment
Route::post('/wallet/increase/callback', 'PaymentController@callback');
Route::post('/wallet/increase', 'PaymentController@do');

/*
|--------------------------------------------------------------------------
| Invitation
|--------------------------------------------------------------------------
|
*/
Route::post('/invitations', 'InvitationController@store');


/*
|--------------------------------------------------------------------------
| Invitation
|--------------------------------------------------------------------------
|
*/
Route::get('/variables', 'VariableController@index');
Route::get('/variables/{key}', 'VariableController@show');





/*
|--------------------------------------------------------------------------
| Version 01
|--------------------------------------------------------------------------
|
*/

Route::get('activities/main', 'ActivityController@showMain');
Route::get('/users/current', 'UserController@currentUser');


/*
|--------------------------------------------------------------------------
| Version 01
|--------------------------------------------------------------------------
|
*/


Route::put('products/action', 'ProductController@act');
Route::put('posts/action', 'PostController@act');
Route::put('categories/action', 'CategoryController@act');
Route::put('users/action', 'UserController@act');



Route::post('catalogs', 'CatalogController@store');
Route::delete('catalogs/{id}', 'CatalogController@destroy');
Route::put('catalogs/{id}', 'CatalogController@update');


Route::get('users/{id}/catalogs', 'UserController@catalogs');



Route::post('stories', 'StoryController@store');


Route::post('products/report', 'ProductController@report');
