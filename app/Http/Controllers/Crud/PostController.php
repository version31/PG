<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class PostController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Post::class);
        $this->crud->setEntity('posts');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id' );
        $this->crud->setColumn('title' );
        $this->crud->setColumn('study_time');
        $this->crud->setColumn('created_at')->format('shamsi');
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
                'name' => 'media_path',
                'type' => 'image',
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
                'name' => 'study_time',
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
