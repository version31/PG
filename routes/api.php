<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('auth/sms/mobile', 'API\UserController@getMobile'); #step1
    Route::post('auth/sms/code', 'API\UserController@getCode'); // #step2 or Login or Register
    Route::post('auth/register', 'API\UserController@registerWithCode'); #step3
    Route::post('auth/password/login', 'API\UserController@loginWithPassword'); #login
    Route::resource('plans', 'API\PlanController')->only(['index' , 'show']);
    Route::resource('cities', 'API\CityController')->only(['index']);







    /**
     *
     * [payments]
     *  ?  paymentable_type = "plans"
     *  ?  paymentable_id  = {plan_id}
     *
     *
     */


    Route::any('/payments/{paymentable_type}/{paymentable_id}/callback', 'API\PaymentController@callback');


});


Route::get('v1/config/ip', 'OptionController@ip'); #2


Route::prefix('v1')->middleware('auth:api')->group(function () {


    Route::get('/payments', 'API\PaymentController@index');

    Route::get('main', 'API\UserController@mainActivity'); #1
    Route::get('/config', 'OptionController@config'); #2
    Route::resource('pages', 'API\PageController')->only([
        'index', 'show'
    ]); #3
    Route::get('profile', 'API\UserController@profile'); #4
    Route::put('profile', 'API\UserController@updateProfile'); #5 todo [add validations]
    Route::post('shops/{id}', 'API\UserController@updateForTest'); #5 todo for test




    Route::get('providers', 'API\UserController@indexProvider'); #7
    Route::get('providers/{id}', 'API\UserController@showProvider'); #7.1
    Route::get('providers/{id}/stories', 'API\UserController@stories'); #8
    Route::get('users/{id}/directs', 'API\UserController@directs'); #9
    Route::get('directs', 'API\DirectController@index'); #10
    Route::put('products/{id}', 'API\ProductController@update'); #todo ???
    Route::delete('products/{id}', 'API\ProductController@destroy');
    Route::put('products/{id}/action', 'API\ProductController@act');
    Route::put('posts/{id}/action', 'API\PostController@act');
    Route::put('categories/{id}/action', 'API\CategoryController@act');
    Route::put('categories/{id}/providers', 'API\CategoryController@providers');
    Route::post('details', 'API\UserController@details');
    Route::resource('services', 'API\ServiceController')->only([
        'index', 'show'
    ]); #


    Route::resource('plans', 'API\PlanController')->except(['index' , 'show']);

    Route::resource('users', 'API\UserController');
    Route::resource('categories', 'API\CategoryController');

    Route::post('stories', 'API\StoryController@store');


    Route::resource('products', 'API\ProductController');
    Route::get('explorer', 'API\ProductController@explorer'); #6

    Route::resource('posts', 'API\PostController');


    /**
     * Operations
     */

    Route::get('/users/{userId}/roles/{roleId}', 'API\TestController@changeRole'); #todo test
    Route::get('test', 'API\PaymentController@test');
    Route::get('clear-logs', 'API\UserController@deleteLog'); #todo for test



});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
