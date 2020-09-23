<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class ProviderController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(User::class);
        $this->crud->setEntity('providers');
        $this->crud->query = $this->crud->query->role('provider');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('last_name')->editColumn(function ($row) {
            return $row->first_name . " " . $row->last_name;
        });
        $this->crud->setColumn('avatar')->editColumn(function ($row) {
            return view('crud::partials.image', ['path' => $row->avatar, 'alt' => $row->name]);
        });
        $this->crud->setColumn('shop_name');
        $this->crud->setColumn('shop_expired_at')->format('shamsi');
        $this->crud->setColumn('mobile');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {

        $this->crud->setField(
            [
                'name' => 'shop_name',
                'validation' => 'required | string',
            ]
        );


        $this->crud->setField(
            [
                'name' => 'first_name',
                'validation' => 'required|string',
            ]
        );

        $this->crud->setField(
            [
                'name' => 'last_name',
                'validation' => 'required|string',
            ]
        );

        $this->crud->setField(
            [
                'name' => 'mobile',
                'validation' => 'required|unique:users,mobile',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'url',
                'name' => 'website',
                'validation' => 'url',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'email',
                'name' => 'email',
                'validation' => 'required|unique:users,email',

            ]
        );


        $this->crud->setField(
            [
                'name' => 'phone',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'name' => 'fax',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'name' => 'gender',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'name' => 'password',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'city',
                'attribute' => 'name',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'number',
                'name' => 'limit_insert_product',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'persian_datepicker',
                'name' => 'shop_expired_at',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'textarea',
                'name' => 'bio',
                'validation' => 'required|string',

            ]
        );


        $this->crud->setField(
            [
                'name' => 'avatar',
                'validation' => 'string',

            ]
        );

        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'roles',
                'attribute' => 'name',

            ]
        );


        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'permissions',
                'attribute' => 'name',

            ]
        );


    }


    public function setupEdit()
    {
        $this->setupCreate();


        $this->crud->setField(
            [

                'name' => 'mobile',
                'validation' => 'required|unique:users,mobile,' . $this->crud->row->id,

            ]
        );


        $this->crud->setField(
            [
                'type' => 'email',
                'name' => 'email',
                'validation' => 'required|unique:users,email,' . $this->crud->row->id,

            ]
        );


        $this->crud->setField(
            [
                'type' => 'persian_datepicker',
                'name' => 'shop_expired_at',

            ]
        );


    }


}
