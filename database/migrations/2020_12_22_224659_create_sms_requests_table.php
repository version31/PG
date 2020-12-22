<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sms_operators', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });


        Schema::create('sms_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->foreignId('operator_id');
            $table->unsignedInteger('count');
            $table->string('body');
            $table->string('file_path')->nullable();
            $table->dateTime('send_at');
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("categories")->onDelete('cascade');
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
            $table->foreign("operator_id")->references("id")->on("sms_operators")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::dropIfExists('sms_requests');
        Schema::dropIfExists('sms_operators');
    }
}
