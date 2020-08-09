<?php

namespace App\Http\Controllers\API_V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $query =  Page::all();
        return new BasicResource($query);
    }


    public function show($slug)
    {
        $query = Page::where('slug', $slug)->first();
        return new BasicResource($query);
    }
}
