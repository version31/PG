<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\Product;
use App\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Product::class);
        $this->crud->setEntity('products');
        $this->crud->query->limit(2);
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('title');
        $this->crud->setColumn('user_name')->format("userLink");
        $this->crud->setColumn('created_at')->format('shamsi');
        $this->crud->setColumn('status')->isStatus('products.status');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {

        $this->crud->setField(
            [
                'name' => 'title',
                'validation' => 'required | string',
            ]
        );

        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'category',
                'attribute' => 'name',
                'validation' => 'required',
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
                'type' => 'mediable',
                'name' => 'mediable',
                'label' => 'اسلاید',
            ]
        );




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
                'name' => 'type',
                'type' => 'select2',
                'options' => config('crud.status.media.type'),
            ]
        );




        $this->crud->setField(
            [
                'name' => 'description',
                'type' => 'textarea',
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
