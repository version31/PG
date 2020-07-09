<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdsToAddablesAndStoryablesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addables', function (Blueprint $table) {
            $table->increments('id')->first();
        });
        Schema::table('storyables', function (Blueprint $table) {
            $table->increments('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addables', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('storyables', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
