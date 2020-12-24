<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFiledsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('shipping');
            $table->integer('shipping_tehran_price')->default(0);
            $table->integer('shipping_others_price')->default(0);
            $table->integer('shipping_tehran_day')->default(0);
            $table->integer('shipping_others_day')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn('shipping_tehran_price');
            $table->dropColumn('shipping_others_price');
            $table->dropColumn('shipping_tehran_day');
            $table->dropColumn('shipping_others_day');
            $table->integer('shipping')->default(0);
        });
    }
}
