<?php

namespace App;


class Order
{
    private $items = [];

    public function setItems(OrderItem $item)
    {
        $this->items[] = $item->get();
    }



    public function getItems()
    {
        return [
            'items' => $this->items,
        ];
    }

    public function getRelatedId()
    {
        return $this->items['related_id'];
    }


    public function getPaymentableId()
    {
        return $this->items['paymentable_id'];
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->items as $item)
            $total = $total + $item['price'];

        return $total;
    }

}
