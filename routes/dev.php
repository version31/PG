<?php


Route::resource('/tests', 'TestController');


Route::get('/columns/{table}', 'DevController@getTableColumns');
Route::get('/columns/{table}/{type]}', 'DevController@getTableColumns');
Route::any('/keys', 'DevController@getKeys');
Route::get('ip', 'DevController@ip'); #2



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

Route::get('/migrate', function () {
    $exitCode = Artisan::call('migrate');
    echo '<h1>migrate is successful</h1>';
    echo '<br>';
    echo 'exitCode: ' . $exitCode;
    die;
});
