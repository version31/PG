<?php

use Illuminate\Database\Seeder;

class BannerPlanSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            [
                "title" => "خانه A",
                "station" => "HOME_A",
                "day_price" => "1000",
            ],

            [
                "title" => "خانه B",
                "station" => "HOME_B",
                "day_price" => "500",
            ]
        ];


        foreach ($items as $item)
            \App\BannerPlan::create($item);

    }
}
