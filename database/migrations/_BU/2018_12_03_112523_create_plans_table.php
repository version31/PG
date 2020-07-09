<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day')->unsigned();
            $table->bigInteger('price')->unsigned();
            /*$table->enum('type',['star','shop','story','priority_product']);*/
            $table->enum('type',['BUY_PROVIDER_PLAN','EXTEND_PROVIDER_PLAN','BUY_PROVIDER_STAR','BUY_STORY','BUY_PROMOTE_PRODUCT','INCREASE_INSERT_PRODUCT']);
            $table->integer('limit_insert_video')->unsigned()->nullable();
            $table->integer('limit_insert_product')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
