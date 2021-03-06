<?php


#--------------------------------------------------
# Login and Register
#--------------------------------------------------
Route::post('auth/sms/mobile', 'UserController@getMobile');             #step1
Route::post('auth/sms/code', 'UserController@getCode');                 // #step2 or Login or Register
Route::post('auth/register', 'UserController@registerWithCode');        #step3
Route::post('auth/login/password', 'UserController@loginWithPassword'); #login


#--------------------------------------------------
# Config
#--------------------------------------------------
Route::get('/config', 'ConfigController@index');                        #2
Route::get('/variables', 'VariableController@index');
Route::get('/variables/{key}', 'VariableController@show');


Route::any('callback', 'ShetabController@callback'); #@todo Do test


Route::middleware('auth:api')->group(function () {

    #--------------------------------------------------
    # new
    #--------------------------------------------------
    Route::get('products/{id}/info', 'ProductController@onSaleDetail');
    Route::get('shop-plans', 'ShopPlanController@index');


    #--------------------------------------------------
    # banners
    #--------------------------------------------------
    Route::post('products/{id}/comments', 'ProductController@commentStore');
    Route::post('posts/{id}/comments', 'PostController@commentStore');

    Route::get('products/{id}/comments', 'ProductController@commentIndex');
    Route::get('posts/{id}/comments', 'PostController@commentIndex');


    #--------------------------------------------------
    # banners
    #--------------------------------------------------
    Route::get('banners/plans', 'BannerController@plans');
    Route::post('banners/requests', 'BannerController@store');


    #--------------------------------------------------
    # sms_requests
    #--------------------------------------------------
    Route::post('sms-requests', 'SmsRequestController@store');
    Route::get('sms-operators', 'SmsRequestController@operators');



    #--------------------------------------------------
    # shopping
    #--------------------------------------------------
    Route::get('shops', 'ShopController@index');
    Route::post('shops', 'ShopController@store');
    Route::get('on-sales', 'OnSaleController@index');
    Route::post('on-sales', 'OnSaleController@store');



    #--------------------------------------------------
    # Activities
    #--------------------------------------------------
    Route::get('activities/main', 'ActivityController@showMain');

    #--------------------------------------------------
    # categories
    #--------------------------------------------------
    Route::get('categories', 'CategoryController@index');
    Route::get('categories/{id}', 'CategoryController@show');
    Route::put('categories/{id}/action', 'CategoryController@act');


    #--------------------------------------------------
    # Catalogs
    #--------------------------------------------------
    Route::post('catalogs', 'CatalogController@store');
    Route::put('catalogs/{id}', 'CatalogController@update');
    Route::delete('catalogs/{id}', 'CatalogController@destroy');


    #--------------------------------------------------
    # Users
    #--------------------------------------------------
    Route::get('users/{id}/catalogs', 'UserController@catalogs');
    Route::put('users/{id}/action', 'UserController@act');


    Route::get('/users/current', 'UserController@currentUser');
    Route::put('users/current', 'UserController@update');
    Route::get('/users/current/info', 'UserController@info');
    Route::get('/users/current/products', 'UserController@products');
    Route::get('/users/current/followers', 'UserController@followers');
    Route::get('/users/current/following', 'UserController@following');
    Route::put('users/current/presentable', 'UserController@updatePresentableFields');


    Route::get('users', 'UserController@index');
    Route::get('users/{id}', 'UserController@show');
    Route::get('users/{id}/stories', 'UserController@stories');
    Route::get('/users/current/balance', 'WalletController@balance');


    #--------------------------------------------------
    #--------------------------------------------------
    # Product
    #--------------------------------------------------
    Route::get('products', 'ProductController@index');
    Route::get('explorer', 'ProductController@explorer');
    Route::get('products/{id}', 'ProductController@show');
    Route::post('products', 'ProductController@store');
    Route::put('products/{id}/action', 'ProductController@act');
    Route::put('products/{id}', 'ProductController@update'); #todo ???
    Route::delete('products/{id}', 'ProductController@destroy');
    Route::post('products/report', 'ProductController@report');


    #--------------------------------------------------
    # wallet
    #--------------------------------------------------
    Route::post('/wallet/transfer', 'WalletController@transfer');
    Route::post('/wallet/money-request', 'WalletController@moneyRequest');
    Route::get('/wallet/transactions', 'WalletController@transactions');
    Route::get('/wallet/buy-plans/{planId}', 'PaymentPlanController@payment');
    Route::post('/wallet/increase', 'ShetabController@do');                #@todo Do test



    #--------------------------------------------------
    # Services
    #--------------------------------------------------
    Route::get('/services', 'ServiceController@index');
    Route::get('/services/{id}', 'ServiceController@show');


    #--------------------------------------------------
    # Products
    #--------------------------------------------------


    #--------------------------------------------------
    # Plans
    #--------------------------------------------------
    Route::get('/plans', 'PlanController@index');


    #--------------------------------------------------
    # Stories
    #--------------------------------------------------
    Route::post('stories', 'StoryController@store');


    #--------------------------------------------------
    # Posts
    #--------------------------------------------------
    Route::get('posts', 'PostController@index');
    Route::post('posts', 'PostController@store');
    Route::get('posts/{id}', 'PostController@show');
    Route::put('posts/{id}', 'PostController@update');
    Route::put('posts/{id}/action', 'PostController@act');


    #--------------------------------------------------
    # Pages
    #--------------------------------------------------
    Route::get('/pages', 'PageController@index');
    Route::get('/pages/{id}', 'PageController@show');


    #--------------------------------------------------
    # Cities
    #--------------------------------------------------
    Route::get('cities', 'CityController@index');


    #--------------------------------------------------
    # Invatation
    #--------------------------------------------------
    Route::post('/invitations', 'InvitationController@store');


    #--------------------------------------------------
    # Plz Check
    #--------------------------------------------------
    Route::get('clear-logs', 'UserController@deleteLog');                   #todo for test
    Route::get('users/{id}/directs', 'UserController@directs');             #9
});









