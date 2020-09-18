<?php




namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use Afracode\CRUD\App\Models\Menu;


class MenuController extends CrudController
{
    public function config()
    {
        $this->crud->setModel(\App\Menu::class);
        $this->crud->SetEntity('menu');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('href');
        $this->crud->setColumn('icon');
        $this->crud->setColumn('permission');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {

        $this->crud->setField(
            [
                'type' => 'mediable',
                'name' => 'photoes',
                'label' => 'فایل ها',
            ]
        );

//
//        $this->crud->setField([
//            'name' => 'href'
//        ]);
//
//        $this->crud->setField([
//            'name' => 'icon'
//        ]);
//
//        $this->crud->setField([
//            'name' => 'permission'
//        ]);
    }
}
