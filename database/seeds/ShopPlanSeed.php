<?php

use Illuminate\Database\Seeder;
use  \App\ShopPlan;

class ShopPlanSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        ShopPlan::create([
            'day' => 5,
            'price' => 1000,
            'maximum_product_on_shop' => 1,
        ]);

        ShopPlan::create([
            'day' => 10,
            'price' => 2000,
            'maximum_product_on_shop' => 2,
        ]);


        ShopPlan::create([
            'day' => 10,
            'price' => 200000,
            'maximum_product_on_shop' => 2,
        ]);
    }
}
