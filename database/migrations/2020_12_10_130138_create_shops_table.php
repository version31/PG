<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->enum('type', ['ON_SALE', 'SHOP']);
            $table->integer('new_price')->nullable();
            $table->date('published_at');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');

            $table->primary(['product_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
