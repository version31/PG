<?php

namespace App\Http\Controllers\Crud;

use Afracode\CRUD\App\Controllers\CrudController;
use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;

class GatewayController extends CrudController
{
    public function config()
    {
        $this->crud->setModel(Payment::class);
        $this->crud->setEntity('payments');
        $this->crud->customActions([]);
    }

    public function setupIndex()
    {
        $this->crud->setColumn('id');
        $this->crud->setColumn('user')->format("userLink");
        $this->crud->setColumn('price' , trans('db.amount'))->format('number');
        $this->crud->setColumn('ref_id' );
        $this->crud->setColumn('tracking_code' )->default('ندارد');
        $this->crud->setColumn('status' )->isStatus('payment.status');
        $this->crud->setColumn('ip' );
        $this->crud->setColumn('created_at' , trans('db.payment_date'))->format("shamsi");
    }
}
