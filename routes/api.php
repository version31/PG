<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| V1
|--------------------------------------------------------------------------
|
*/
Route::group([
    'namespace'  => 'API',
    'prefix'     => 'v1',
], function ($router) {
    require base_path('routes/api.v1.php');
});


/*
|--------------------------------------------------------------------------
| V2
|--------------------------------------------------------------------------
|
*/
Route::group([
    'namespace'  => 'API_V2',
    'prefix'     => 'v2',
], function ($router) {
    require base_path('routes/api.v2.php');
});



