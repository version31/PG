<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::unprepared('
        drop trigger increase_count_like_tables;
        drop trigger increase_count_video_and_product_tables;
        drop trigger decrease_count_like_tables;
        drop trigger decrease_count_video_and_product_tables;
        ');


        /*after user like post or product we set trigger for add
        count_like in other tables*/
        DB::unprepared('
        CREATE TRIGGER increase_count_like_tables AFTER INSERT ON likables
        FOR EACH ROW
        BEGIN
        IF NEW.likable_type = \'App\Post\' THEN
        UPDATE posts SET count_like=count_like + 1 where id = NEW.likable_id;
        ELSEIF NEW.likable_type = \'App\Product\' THEN
        UPDATE products SET count_like=count_like + 1 where id = NEW.likable_id;
        UPDATE categories as a
        join products b on b.category_id = a.id
        SET a.count_like=a.count_like + 1 WHERE b.id = NEW.likable_id;
        UPDATE users as a
        join products b on b.id = NEW.likable_id
        SET a.count_like=a.count_like + 1 WHERE a.id = b.user_id;
        END IF;
        END
        ');

        /*after product added by user should update all tables that
        have count_video,count_product*/

        db::unprepared('
        CREATE TRIGGER increase_count_video_and_product_tables AFTER INSERT ON products
        FOR EACH ROW
        BEGIN
        UPDATE users SET count_product=count_product + 1 where id = NEW.user_id;
        UPDATE categories SET count_product=count_product + 1 where id = NEW.category_id;
        IF NEW.type = \'video\' THEN
        UPDATE users SET count_video=count_video + 1 where id = NEW.user_id;
        UPDATE categories SET count_video=count_video + 1 where id = NEW.category_id;
        END IF;
        END
        ');

        /*after user unlike a post or product we decrease count_like in all tables*/
        db::unprepared('CREATE TRIGGER decrease_count_like_tables AFTER DELETE ON likables
        FOR EACH ROW
        BEGIN
        IF OLD.likable_type = \'App\Post\' THEN
        UPDATE posts SET count_like=count_like - 1 where id = OLD.likable_id;
        ELSEIF OLD.likable_type = \'App\Product\' THEN
        UPDATE products SET count_like=count_like - 1 where id = OLD.likable_id;
        UPDATE categories as a
        join products b on b.category_id = a.id
        SET a.count_like=a.count_like - 1 WHERE b.id = OLD.likable_id;
        UPDATE users as a
        join products b on b.id = OLD.likable_id
        SET a.count_like=a.count_like - 1 WHERE a.id = b.user_id;
        END IF;
        END
        ');

        /*after remove product by admin we decrease count_video and count_product
        in all tables*/

        db::unprepared('
        CREATE TRIGGER decrease_count_video_and_product_tables AFTER DELETE ON products
        FOR EACH ROW
        BEGIN
        UPDATE users SET count_product=count_product - 1 where id = OLD.user_id;
        UPDATE categories SET count_product=count_product - 1 where id = OLD.category_id;
        IF OLD.type = \'video\' THEN
        UPDATE users SET count_video=count_video - 1 where id = OLD.user_id;
        UPDATE categories SET count_video=count_video - 1 where id = OLD.category_id;
        END IF;
        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('
        drop trigger increase_count_like_tables;
        drop trigger increase_count_video_and_product_tables;
        drop trigger decrease_count_like_tables;
        drop trigger decrease_count_video_and_product_tables;
        ');
    }
}
