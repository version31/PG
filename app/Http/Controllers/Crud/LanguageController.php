<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Category;
use App\Http\Controllers\Controller;
use App\User;
use App\Variable;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\TranslationLoader\LanguageLine;

class LanguageController extends CrudController
{





    public function config()
    {
        $this->crud->setModel(LanguageLine::class);
        $this->crud->setEntity('languages');
        //        $this->crud->customActions(['edit']);
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('group');
        $this->crud->setColumn('key');
        $this->crud->setColumn('text')->editColumn(function ($row) {
            return trans($row->group.".".$row->key);
        });
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {
        $this->crud->setField([
            'name' => 'group',
            'value' => LanguageLine::orderBy('id','desc')->first()->group,
            'validation' => 'required',
        ]);

        $this->crud->setField([
            'name' => 'key',
            'validation' => 'required',
        ]);

        $this->crud->setField([
            'name' => 'fa',
            'type' => 'lang',
        ]);

    }



    public function setupEdit()
    {
        $this->crud->setField([
            'name' => 'group',
        ]);

        $this->crud->setField([
            'name' => 'key',
        ]);

        $this->crud->setField([
            'name' => 'text',
            'type' => 'json',
        ]);

    }




    public function edit($id)
    {
        $this->crud->resetFields();
        $this->crud->setRow($id);
        $this->setupEdit();
        $this->crud->setDefaults();


        return view('crud::edit',
            [
                'crud' => $this->crud
            ]
        );
    }


    public function store(Request $request)
    {


//        return 8;

        $path = $this->crud->tmpPath;

        $this->setupCreate();

        $this->validate($request, $this->crud->getValidations());

        $fields = $request->only(['group' , 'key' , 'text']);




//        $fields["text"] = ['en' => 'This is a required field', 'nl' => 'Dit is een verplicht veld'];
//        $fields["text"] = $request->all()['text'];


//        dd() ;


        $new = $this->crud->model::create($fields);




        return redirect()->back()->with('success', trans('message.created'));
    }




}
