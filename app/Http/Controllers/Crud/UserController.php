<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\User;
use Illuminate\Http\Request;

class UserController extends CrudController
{


    public function config()
    {
        $this->crud->setModel(User::class);
        $this->crud->setEntity('users');
        $this->crud->query = $this->crud->query->role('user');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('last_name')->editColumn(function ($row) {
            return $row->first_name . " " . $row->last_name;
        });
        $this->crud->setColumn('mobile');
        $this->crud->setColumn('avatar')->editColumn(function ($row) {
            return view('crud::partials.image', ['path' => $row->avatar, 'alt' => $row->name]);
        });
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {

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
                'name' => 'shop_name',
                'validation' => 'required | string',
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
                'type' => 'password',
                'name' => 'password',
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


        $this->crud->setField([
            'name' => "avatar",
            'type' => 'image',
            'aspect_ratio' => 1, // set to 0 to allow any aspect ratio
            'crop' => true,      // set to true to allow cropping, false to disable
        ]);

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

    //    public function update(Request $request, $id)
    //    {
    //        $this->crud->setRow($id);
    //        $this->setupEdit();
    //
    //
    //        $this->validate($request, array_merge($this->crud->getValidations()));
    //
    //        $input =  $fields = $this->crud->getFormInputs($request);
    //
    //        {
    //            $input = \Arr::except($input, array('password'));
    //
    //        }
    //
    //
    //        dd($input);
    //        $this->crud->row->update($input);
    //        DB::table('model_has_roles')->where('model_id', $id)->delete();
    //        DB::table('model_has_permissions')->where('model_id', $id)->delete();
    //
    //
    //        if ($this->crud->hasTrait('HasRoles')) {
    //            $this->crud->row->assignRole($request->input('roles'));
    //            $this->crud->row->givePermissionTo($request->input('permissions'));
    //        }
    //
    //
    //        $input = $request->all();
    //        if (!empty($input['password'])) {
    //            $input['password'] = Hash::make($input['password']);
    //        } else {
    //            $input = $request->except(['password']);
    //        }
    //
    //
    //        if ($this->crud->hasTrait('InteractsWithMedia')) {
    //            $media = $this->crud->row->getMedia('*')->pluck('file_name')->toArray();
    //
    //            foreach ($request->input('mediable', []) as $file) {
    //                if (count($media) === 0 || !in_array($file, $media)) {
    //                    $this->crud->row->addMedia(storage_path('tmp/' . $file))->toMediaCollection();
    //                }
    //            }
    //        }
    //
    //        return $request->all();
    //    }


}
