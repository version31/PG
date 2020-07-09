<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('city_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('shop_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('mobile')->unique();
            $table->string('website')->nullable();
            $table->text('bio')->nullable();
            $table->enum('gender',['male','female']);
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->text('address');
            $table->string('password');
            $table->integer('count_video')->unsigned()->default(0);
            $table->integer('count_product')->unsigned()->default(0);
            $table->integer('count_like')->unsigned()->default(0);
            $table->integer('limit_insert_product')->unsigned();
            $table->integer('limit_insert_video')->unsigned();
            $table->timestamps();
            $table->timestamp('shop_expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
