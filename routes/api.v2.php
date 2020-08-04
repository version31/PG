<?php


Route::post('auth/sms/mobile', 'UserController@getMobile'); #step1
Route::post('auth/sms/code', 'UserController@getCode'); // #step2 or Login or Register
Route::post('auth/register', 'UserController@registerWithCode'); #step3


Route::get('providers', 'ProviderController@index'); #7








Route::get('/payment-plans/{planId}', 'PaymentPlanController@payment');

Route::post('/wallet/transfer', 'WalletController@transfer');
Route::post('/wallet/money-request', 'WalletController@moneyRequest');
