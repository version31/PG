<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Category;
use App\Http\Controllers\Controller;
use App\Service;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Spatie\TranslationLoader\LanguageLine;

class ServiceController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Service::class);
        $this->crud->setEntity('services');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('title');
        $this->crud->setColumn('user_name')->format("userLink");
        $this->crud->setColumn('created_at')->format('shamsi');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {


        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'user',
                'attribute' => 'name',
                'validation' => 'required',
            ]
        );

        $this->crud->setField(
            [
                'name' => 'avatar',
                'type' => 'image',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'media_path',
                'type' => 'image',
            ]
        );



        $this->crud->setField(
            [
                'name' => 'title',
                'validation' => 'required | string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'headline',
                'validation' => 'string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'body',
                'type' => 'tinymce_rtl',
                'validation' => 'required|string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'confirmed_at',
                'type' => 'persian_datepicker',
                'validation' => 'required|string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'promote_expired_at',
                'type' => 'persian_datepicker',
                'validation' => 'required|string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'status',
                'type' => 'select2',
                'options' => config('crud.status.products.status'),
            ]
        );



    }


    public function setupEdit()
    {
        $this->setupCreate();
    }


}
