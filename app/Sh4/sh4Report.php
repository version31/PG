<?php

namespace App\Sh4;

use App\Bookmarkable;
use App\Category;
use App\Http\Requests\ActionRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Likeable;
use App\Product;
use App\Reportable;
use App\User;
use Auth;
use Bavix\Wallet\Services\CommonService;
use Illuminate\Http\Request;
use Result;

trait sh4Report
{

    public $errors = [];


    public function report(ReportRequest $request)
    {
        $models = [
            'App\Http\Controllers\API_V2\PostController' => 'App\Post',
            'App\Http\Controllers\API_V2\CategoryController' => 'App\Category',
            'App\Http\Controllers\API_V2\ProductController' => 'App\Product',
            'App\Http\Controllers\API_V2\UserController' => 'App\User',
        ];


        $report = Reportable::create([
            'user_id' => Auth::id(),
            'reportable_type' => $models[get_class($this)],
            'reportable_id' => $request->get("id"),
            "body" => $request->get("body")
        ]);


        if ($report)
            return new SuccessResource();

    }


}
