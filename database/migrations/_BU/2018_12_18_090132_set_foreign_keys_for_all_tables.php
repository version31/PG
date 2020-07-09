<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetForeignKeysForAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign("city_id")->references("id")->on("cities");
            $table->foreign("role_id")->references("id")->on("roles");
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
        Schema::table('bookmarkables', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("service_id")->references("id")->on("services");
        });
        Schema::table('services', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
        Schema::table('directs', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("receiver_id")->references("id")->on("users");
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
            $table->foreign("category_id")->references("id")->on("categories")->onDelete('cascade');
        });
        Schema::table('likables', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->foreign("parent_id")->references("id")->on("cities");
        });
        Schema::table('stars', function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign("city_id")->references("id")->on("cities");
            $table->dropForeign("role_id")->references("id")->on("roles");
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
        });
        Schema::table('bookmarkables', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
            $table->dropForeign("service_id")->references("id")->on("services");
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
        });
        Schema::table('directs', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
            $table->dropForeign("receiver_id")->references("id")->on("users");
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
            $table->dropForeign("category_id")->references("id")->on("categories");
        });
        Schema::table('likables', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign("parent_id")->references("id")->on("cities");
        });
        Schema::table('stars', function (Blueprint $table) {
            $table->dropForeign("user_id")->references("id")->on("users");
        });
    }
}
