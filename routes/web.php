<?php



/*
|--------------------------------------------------------------------------
| Dev tools
|--------------------------------------------------------------------------
|
*/
Route::group([
    'namespace'  => 'Dev',
    'prefix'     => 'dev',
], function ($router) {
    require base_path('routes/dev.php');
});




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/






//
//Route::view('/axios', 'axios'); #todo removed
//
//Route::resource('tests', 'TestController');


//Route::get('/', function () {
//    return redirect()->route('login');
//});
/*show admin page and plans index/create/edit
 * add route name such as
 * admin.plans.index
 * admin.plans.edit
*/


//Route::get('/home', function () {
//    abort(401);
//});


Route::get('/test', 'TestController@index');

Route::prefix('admin')->group(function () {
// Authentication Routes...
    Route::get('login', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => '',
        'uses' => 'Auth\LoginController@login'
    ]);
    Route::post('logout', [
        'as' => 'logout',
        'uses' => 'Auth\LoginController@logout'
    ]);

// Password Reset Routes...
    Route::post('password/email', [
        'as' => 'password.email',
        'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
    ]);
    Route::get('password/reset', [
        'as' => 'password.request',
        'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
    ]);
    Route::post('password/reset', [
        'as' => 'password.update',
        'uses' => 'Auth\ResetPasswordController@reset'
    ]);
    Route::get('password/reset/{token}', [
        'as' => 'password.reset',
        'uses' => 'Auth\ResetPasswordController@showResetForm'
    ]);

// Registration Routes...
    Route::get('register', [
        'as' => 'register',
        'uses' => 'Auth\RegisterController@showRegistrationForm'
    ]);
    Route::post('register', [
        'as' => '',
        'uses' => 'Auth\RegisterController@register'
    ]);

});

//Route::get('/admin/updateCount', 'OptionController@updateCount'); #3

Route::prefix('admin')->middleware('admin')->namespace('Admin')->name('admin.')->group(function () {

    /*routes for plan section contain index/edit/show/delete*/



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
/*
 * authentication routes*/



//

Route::get('/clear-all', function () {
    $exitCode[] = Artisan::call('view:clear');
    $exitCode[] = Artisan::call('cache:clear');
    $exitCode[] = Artisan::call('config:cache');
    $exitCode[] = Artisan::call('route:clear');
    echo '<h1>All Sections cleared</h1>';
    echo '<br>';
    print_r($exitCode);
    die;
});


//Route::view('/pwa/{path?}', 'app');






//
//
//Route::get('{any}', function () {
//    return view('app');
//})->where('any','.*');
