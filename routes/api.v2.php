<?php


#--------------------------------------------------
# Login and Register
#--------------------------------------------------
Route::post('auth/sms/mobile', 'UserController@getMobile'); #step1
Route::post('auth/sms/code', 'UserController@getCode'); // #step2 or Login or Register
Route::post('auth/register', 'UserController@registerWithCode'); #step3
Route::post('auth/login/password', 'UserController@loginWithPassword'); #login



#--------------------------------------------------
# Config
#--------------------------------------------------
Route::get('/config', 'ConfigController@index'); #2
Route::get('/variables', 'VariableController@index');
Route::get('/variables/{key}', 'VariableController@show');






Route::middleware('auth:api')->group(function () {
    #--------------------------------------------------
    # Activities
    #--------------------------------------------------
    Route::get('activities/main', 'ActivityController@showMain');

    #--------------------------------------------------
    # categories
    #--------------------------------------------------
    Route::get('categories', 'CategoryController@index');
    Route::get('categories/{id}', 'CategoryController@show');
    Route::put('categories/action', 'CategoryController@act');




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
    Route::put('users/action', 'UserController@act');


    Route::get('/users/current', 'UserController@currentUser');
    Route::get('users/{id}', 'UserController@show');
    Route::get('/users/current/show-status', 'UserController@showStatus');
    Route::put('users/current', 'UserController@update');
    Route::put('users/current/presentable', 'UserController@updatePresentableFields');
    Route::get('users', 'UserController@index');
    Route::get('users/{id}/stories', 'UserController@stories');


    #--------------------------------------------------
    # Product
    #--------------------------------------------------
    Route::get('products', 'ProductController@index');
    Route::get('explorer', 'ProductController@explorer');
    Route::get('products/{id}', 'ProductController@show');
    Route::post('products', 'ProductController@store');
    Route::put('products/action', 'ProductController@act');
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
    Route::post('/wallet/increase', 'PaymentController@do'); #@todo Do test
    Route::post('/wallet/increase/callback', 'PaymentController@callback'); #@todo Do test






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
    Route::get('posts/{id}', 'PostController@show');
    Route::put('posts/action', 'PostController@act');



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
    Route::get('clear-logs', 'UserController@deleteLog'); #todo for test
    Route::get('users/{id}/directs', 'UserController@directs'); #9
    Route::get('directs', 'DirectController@index'); #10
});









