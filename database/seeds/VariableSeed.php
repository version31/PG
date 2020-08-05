<?php

use Illuminate\Database\Seeder;

use  \App\Variable;

class VariableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Variable::create([
            'name' => 'درصد هدیه ثبت نام',
            'key' => 'gift_register',
            'value' => 22,
        ]);

        Variable::create([
            'name' => "تجاری سازی پروفایل",
            'key' => 'BUY_PROVIDER_PLAN',
            'value' => "تجاری سازی پروفایل",
        ]);

        Variable::create([
            'name' => "تمدید پنل فروشگاهی",
            'key' => "EXTEND_PROVIDER_PLAN",
            'value' => "تمدید پنل فروشگاهی",
        ]);

        Variable::create([
            'name' => "خرید ستاره",
            'key' => "BUY_PROVIDER_STAR",
            'value' => "خرید ستاره",
        ]);

        Variable::create([
            'name' => "خرید استوری",
            'key' => "BUY_STORY",
            'value' => "خرید استوری",
        ]);

        Variable::create([
            'name' => "ویژه سازی محصول",
            'key' => "BUY_PROMOTE_PRODUCT",
            'value' => "ویژه سازی محصول",
        ]);

        Variable::create([
            'name' => "خرید بسته درج محصول",
            'key' => "INCREASE_INSERT_PRODUCT",
            'value' => "خرید بسته درج محصول",
        ]);

    }
}
