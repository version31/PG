<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->integer("user_id")->unsigned();
            $table->string("media_path");
            $table->enum("type",['picture','video']);
            $table->string("title");
            $table->integer("count_like")->default(0)->unsigned();
            $table->text("description");
            $table->timestamps();
            $table->timestamp("confirmed_at")->nullable();
            $table->timestamp("promote_expired_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
