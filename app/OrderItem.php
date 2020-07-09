<?php

namespace App;


use Carbon\Carbon;

class OrderItem
{
    private $paymentableType;
    private $paymentableId;
    private $relatedId;
    private $model;
    private $price;
    private $log;

    public function __construct( $paymentableType , $paymentableId , $relatedId = null)
    {
        $this->setPaymentableType($paymentableType);
        $this->setPaymentableId($paymentableId);
        $this->setRelatedId($relatedId);
        $this->setModel();
        $this->setPrice();
        $this->setLog();
    }


    private function getRelatedId()
    {
        return $this->relatedId;
    }


    private function setRelatedId($relatedId): void
    {
        $this->relatedId = $relatedId;
    }


    private function setModel(): void
    {
        $this->model = app($this->getPaymentableType());
    }


    private function getModel()
    {
        return $this->model;
    }


    public function getLog()
    {
        return $this->log;
    }


    public function setLog()
    {
        $log = $this->getModel()->where('id' , $this->getPaymentableId())->first()->toArray();
        $log['created_at'] = Carbon::now()->toDateTimeString();
        unset($log['id']);
        unset($log['price']);
        $this->log = $log;
    }


    private function getPaymentableId()
    {
        return $this->paymentableId;
    }


    private function setPaymentableId($paymentableId): void
    {
        $this->paymentableId = $paymentableId;
    }


    private function getPaymentableType()
    {
        return $this->paymentableType;
    }


    private function setPaymentableType($paymentableType): void
    {
        $this->paymentableType = $paymentableType;
    }


    private function getPrice()
    {
        return $this->price;
    }


    private function setPrice(): void
    {
        $this->price = $this->getModel()->where('id' , $this->getPaymentableId())->first()->price;
    }


    public function get()
    {
        return [
            'paymentable_id' => $this->getPaymentableId(),
            'paymentable_type' => $this->getPaymentableType(),
            'price' => $this->getPrice(),
            'related_id' => $this->getRelatedId(),
            'log' => $this->getLog(),
        ];
    }

}
