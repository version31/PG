<?php

namespace App\Http\Controllers\API;

use App\Storyable;
use Result;
use App\Service;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    //
    public function index()
    {
        $services = Service::orderBy('id','Desc')->get();
        $data['services'] = $services;
        $data['stories'] = $this->stories();
        return Result::setData($data)->get();
    }

    public function show($id)
    {
        $service = Service::where('id', $id)->with(['addables', 'stories'])->first();
        $data = ['service' => $service];
        return Result::setData($data)->get();
    }

    private function stories()
    {
        return Storyable::where('storyable_type' , Service::class)->isVisible()->get();
    }
}
