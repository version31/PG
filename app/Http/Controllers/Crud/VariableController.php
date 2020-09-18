<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Category;
use App\Http\Controllers\Controller;
use App\User;
use App\Variable;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class VariableController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Variable::class);
        $this->crud->setEntity('variables');
        $this->crud->customActions(['edit']);
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('key');
        $this->crud->setColumn('name');
        $this->crud->setColumn('value');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {
        $this->crud->setField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'نام',
        ]);

        $this->crud->setField([
            'name' => 'key',
            'type' => 'text',
            'label' => 'کلید',
        ]);

        $this->crud->setField([
            'name' => 'value',
            'type' => 'text',
            'label' => 'مقدار',
        ]);
    }


    public function setupEdit()
    {
        $this->setupCreate();
        $this->crud->removeField('key');
    }






}
