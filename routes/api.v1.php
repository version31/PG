<?php
/**
 *
 * [payments]
 *  ?  paymentable_type = "plans"
 *  ?  paymentable_id  = {plan_id}
 *
 *
 */
Route::any('/payments/{paymentable_type}/{paymentable_id}/callback', 'PaymentController@callback');

Route::post('auth/sms/mobile', 'UserController@getMobile'); #step1
Route::post('auth/sms/code', 'UserController@getCode'); // #step2 or Login or Register
Route::post('auth/register', 'UserController@registerWithCode'); #step3

Route::post('auth/password/login', 'UserController@loginWithPassword'); #login
Route::resource('plans', 'PlanController')->only(['index' , 'show']);
Route::resource('cities', 'CityController')->only(['index']);
Route::get('config/ip', 'OptionController@ip'); #2


Route::middleware('auth:api')->group(function () {
    Route::get('/payments', 'PaymentController@index');
    Route::get('main', 'UserController@mainActivity'); #1
    Route::get('/config', 'OptionController@config'); #2
    Route::resource('pages', 'PageController')->only([
        'index', 'show'
    ]); #3
    Route::get('profile', 'UserController@profile'); #4
    Route::put('profile', 'UserController@updateProfile'); #5 todo [add validations]
    Route::post('shops/{id}', 'UserController@updateForTest'); #5 todo for test
    Route::get('providers', 'UserController@indexProvider'); #7
    Route::get('providers/{id}', 'UserController@showProvider'); #7.1
    Route::get('providers/{id}/stories', 'UserController@stories'); #8
    Route::get('users/{id}/directs', 'UserController@directs'); #9
    Route::get('directs', 'DirectController@index'); #10
    Route::put('products/{id}', 'ProductController@update'); #todo ???
    Route::delete('products/{id}', 'ProductController@destroy');
    Route::put('products/{id}/action', 'ProductController@act');
    Route::put('posts/{id}/action', 'PostController@act');
    Route::put('categories/{id}/action', 'CategoryController@act');
    Route::put('categories/{id}/providers', 'CategoryController@providers');
    Route::post('details', 'UserController@details');
    Route::resource('services', 'ServiceController')->only([
        'index', 'show'
    ]); #
    Route::resource('plans', 'PlanController')->except(['index' , 'show']);
    Route::resource('users', 'UserController');
    Route::resource('categories', 'CategoryController');
    Route::post('stories', 'StoryController@store');
    Route::resource('products', 'ProductController');
    Route::get('explorer', 'ProductController@explorer'); #6
    Route::resource('posts', 'PostController');
    /**
     * Operations
     */
    Route::get('/users/{userId}/roles/{roleId}', 'TestController@changeRole'); #todo test
    Route::get('test', 'PaymentController@test');
    Route::get('clear-logs', 'UserController@deleteLog'); #todo for test
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
