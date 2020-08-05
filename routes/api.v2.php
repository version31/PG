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



/*
|--------------------------------------------------------------------------
| Payment
|--------------------------------------------------------------------------
|
*/
Route::get('/payment-plans/{planId}', 'PaymentPlanController@payment');



/*
|--------------------------------------------------------------------------
| Wallet
|--------------------------------------------------------------------------
|
*/
Route::post('/wallet/transfer', 'WalletController@transfer');
Route::post('/wallet/money-request', 'WalletController@moneyRequest');
Route::get('/wallet/transactions', 'WalletController@transactions');



/*
|--------------------------------------------------------------------------
| Invitation
|--------------------------------------------------------------------------
|
*/
Route::post('/invitations', 'InvitationController@store');



