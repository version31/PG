<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\Storyable;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class StoryController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Storyable::class);
        $this->crud->setEntity('stories');
        $this->crud->query->where('storyable_type' , User::class)->with('storyable');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('title');
        $this->crud->setColumn('user_name')->editColumn(function ($row) {
            $title = $row->storyable->name ?? null;
            $href = '/admin/users/' . $row->storyable ?: $row->storyable->id ;
            return view('crud::partials.link', compact('title', 'href'));
        });
        $this->crud->setColumn('status')->isStatus('storyables.status');
        $this->crud->setColumn('expired_at')->format('shamsi');
        $this->crud->setColumn('created_at')->format('shamsi');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {
        $this->crud->setField([
            'name'         => "media_path",
            'type'         => 'image',
            'aspect_ratio' => 1, // set to 0 to allow any aspect ratio
            'crop'         => true, // set to true to allow cropping, false to disable
        ]);

        $this->crud->setField([
            'name'         => "media_path",
            'type'         => 'image',
            'aspect_ratio' => 1, // set to 0 to allow any aspect ratio
            'crop'         => true, // set to true to allow cropping, false to disable
        ]);
    }

    public function setupCreate1()
    {



        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'storyable',
                'attribute' => 'title',
                'validation' => 'required',
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
                'name' => 'media_path',
                'type' => 'image',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'expired_at',
                'type' => 'persian_datepicker',
                'validation' => 'required|string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'status',
                'type' => 'select2',
                'options' => config('crud.status.storyables.status'),
            ]
        );



        $this->crud->setField(
            [
                'name' => 'type',
                'type' => 'select2',
                'options' => config('crud.status.media.type'),
            ]
        );

    }


    public function setupEdit()
    {
        $this->setupCreate();
    }


}
