<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoryablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storyables', function (Blueprint $table) {
            $table->integer('storyable_id')->unsigned();
            $table->string('storyable_type');
            $table->string('title');
            $table->string("media_path");
            $table->timestamp('expired_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storyables');
    }
}
