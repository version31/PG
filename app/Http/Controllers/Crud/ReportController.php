<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Category;
use App\Http\Controllers\Controller;
use App\Reportable;
use App\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class ReportController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(Reportable::class);
        $this->crud->setEntity('reports');
        $this->crud->customActions(['delete']);
        $this->crud->query->with('user');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('body');
        $this->crud->setColumn('created_at')->format('shamsi');
        $this->crud->setColumn('user_name')->format('userLink');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {

        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'shop_name',
                'validation' => 'required | string',
                'label' => 'نام تجاری|شرکت|فروشگاه|..',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'first_name',
                'validation' => 'required|string',
                'label' => 'نام',
            ]
        );

        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'last_name',
                'validation' => 'required|string',
                'label' => 'نام خانوادگی',
            ]
        );

        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'mobile',
                'validation' => 'required|unique:users,mobile',
                'label' => 'موبایل',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'url',
                'name' => 'website',
                'validation' => 'url',
                'label' => 'وب سایت',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'email',
                'name' => 'email',
                'validation' => 'required|unique:users,email',
                'label' => 'ایمیل',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'phone',
                'validation' => 'required|string',
                'label' => 'تلفن ثابت',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'fax',
                'validation' => 'required|string',
                'label' => 'فکس',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'gender',
                'validation' => 'required|string',
                'label' => 'جنسیت',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'password',
                'validation' => 'required|string',
                'label' => 'رمز عبور',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'city',
                'attribute' => 'name',
                'label' => 'شهر',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'number',
                'name' => 'limit_insert_product',
                'validation' => 'required|string',
                'label' => 'محدودیت تعداد محصول',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'persian_datepicker',
                'name' => 'shop_expired_at',
                'validation' => 'required|string',
                'label' => 'تاریخ انقضای فروشگاه',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'textarea',
                'name' => 'bio',
                'validation' => 'required|string',
                'label' => 'بیوگرافی',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'avatar',
                'validation' => 'string',
                'label' => 'تصویر آواتار',
            ]
        );

        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'roles',
                'attribute' => 'name',
                'label' => 'نقش ها',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'relation',
                'method' => 'permissions',
                'attribute' => 'name',
                'label' => 'اجازه های دسترسی',
            ]
        );


    }


    public function setupEdit()
    {
        $this->setupCreate();


        $this->crud->setField(
            [
                'type' => 'text',
                'name' => 'mobile',
                'validation' => 'required|unique:users,mobile,' . $this->crud->row->id,
                'label' => 'موبایل',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'email',
                'name' => 'email',
                'validation' => 'required|unique:users,email,' . $this->crud->row->id,
                'label' => 'ایمیل',
            ]
        );


        $this->crud->setField(
            [
                'type' => 'persian_datepicker',
                'name' => 'shop_expired_at',
                'label' => 'تاریخ انقضای فروشگاه',
            ]
        );


    }


}
