<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use Illuminate\Http\Request;

class PlanController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Plan::class);
        $this->crud->setEntity('plans');
        $this->crud->customActions(['edit']);


    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('type')->format('star');
        $this->crud->setColumn('price')->format('number');
        $this->crud->setColumn('day')->default("فاقد محدودیت زمانی");
        $this->crud->setColumn('limit_insert_product')->default('فاقد بسته محصول');
        $this->crud->setColumn('action');
    }


    public function setupEdit()
    {

        $this->crud->setField([
            'name' => 'price',
            'type' => 'number',
            'label' => 'قیمت (تومان)',
            "validation" => "required"
        ]);


        $this->crud->setField([
            'name' => 'extra',
            'type' => 'number',
            'label' => 'تعداد ستاره',
        ]);


        $this->crud->setField([
            'name' => 'limit_insert_product',
            'type' => 'number',
            'label' => 'بسته محصول',
        ]);



        $this->crud->setField([
            'name' => 'day',
            'type' => 'number',
            'label' => 'مدت (روز)',
        ]);








    }




}
