<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Category;
use App\Http\Controllers\Controller;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class CategoryController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Category::class);
        $this->crud->setEntity('categories');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('name');
        $this->crud->setColumn('count_product' );
        $this->crud->setColumn('created_at' )->format('shamsi');
        $this->crud->setColumn('action');
    }






    public function setupCreate()
    {

        $this->crud->setField(
            [
                'name' => 'name',
                'validation' => 'required | string',
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
                'name' => 'avatar',
                'type' => 'image',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'order',
                'type' => 'number',
                'validation' => 'numeric',
            ]
        );



    }



    public function setupEdit()
    {
        $this->setupCreate();

    }





}
