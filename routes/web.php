<?php
/*
|--------------------------------------------------------------------------
| Dev tools
|--------------------------------------------------------------------------
|
*/


Route::prefix('admin')->group(function () {
    Auth::routes();
});

Route::get('/', function () {
    return redirect('admin/login');
});
Route::get('/home', function () {
    return redirect('admin');
});

Route::prefix('admin')->middleware('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/users/current/password', 'UserController@createPassword')->name('changePassword');
    Route::put('/users/current/password', 'UserController@updatePassword')->name('updatePassword');
    Route::get('/', function () {
        return redirect('admin/directs');
    });
    Route::resource('plans', 'PlanController');
    /*routes for post section contain index/edit/show/delete*/
    Route::resource('posts', 'PostController');
    Route::resource('pages', 'PageController');
    /*routes for user section contain index/edit/show/delete*/
    Route::get('users/admin/edit', 'UserController@editAdmin');
    Route::resource('users', 'UserController');
    Route::get('users/{id}/role', 'UserController@editRole')->name('users.role.edit');
    Route::put('users/role/{id}', 'UserController@updateRole')->name('users.role');
    Route::resource('providers', 'ProviderController');
    /*routes for service section contain index/show/delete*/
    Route::resource('services', 'ServiceController');
    /*routes for service requests contain index/edit/delete*/
    Route::resource('serviceRequests', 'ServiceRequestController',
        [
            'except' => ['show', 'create', 'store']
        ]);
    /*routes for stories index/edit/show/delete*/
    Route::resource('stories', 'StoryController');
    /*routes for my stories(services) index/edit/show/delete*/
    Route::resource('service-stories', 'StoryController');
    /*routes for directs index/show/delete*/
    Route::resource('directs', 'DirectController')->only(['show', 'destroy', 'index']);
    /*routes for admin directs index/show/delete*/
    Route::resource('adminDirects', 'AdminDirectController')->only(['show', 'destroy', 'index', 'store']);
    /*routes for products*/
    Route::get('products/{id}/status/{show}', 'ProductController@changeStatus');
    Route::resource('products', 'ProductController');
    /*routes for options*/
    Route::resource('options', 'OptionController')->only('index', 'update');
    /*routes for magazines*/
    Route::resource('magazines', 'MagazineController');
    /*routes for priority products*/
    Route::resource('priorityProducts', 'PriorityProductController');
    /*routes for priority pages*/
    Route::resource('priorityPages', 'PriorityPageController');
    /*routes for provider requests*/
    Route::resource('providerRequests', 'ProviderRequestController');
});


Route::resource('users', 'Crud\UserController');
Route::resource('providers', 'Crud\ProviderController');
Route::resource('pages', 'Crud\PageController');
Route::resource('posts', 'Crud\PostController');
Route::resource('products', 'Crud\ProductController');
Route::resource('categories', 'Crud\CategoryController');
Route::resource('services', 'Crud\ServiceController');
Route::resource('stories', 'Crud\StoryController');
Route::resource('plans', 'Crud\PlanController');
Route::resource('variables', 'Crud\VariableController');
Route::resource('transactions', 'Crud\TransactionController');
Route::resource('reports', 'Crud\ReportController');
Route::resource('languages', 'Crud\LanguageController');
Route::resource('payments', 'Crud\GatewayController');
Route::resource('menu', 'Crud\MenuController');


Route::get('datatable/products', 'Crud\ProductController@datatable');
Route::get('datatable/services', 'Crud\ServiceController@datatable');



Route::get('uploads/{file}', function ($file) {
    return Storage::disk('public')->response('/uploads/'.$file);
});
