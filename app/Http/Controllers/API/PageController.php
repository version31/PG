<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;
use Result;

class PageController extends Controller
{

    public function index()
    {

        $records =  Page::all();

        return Result::setData($records)->get();
    }


    public function show($slug)
    {

        $record = Page::where('slug', $slug)->first();

        return Result::setData($record)->get();

    }


}
