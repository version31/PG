<?php

namespace App\Http\Controllers;

use App\Category;
use App\Likeable;
use App\Post;
use App\Product;
use App\User;
use phpDocumentor\Reflection\DocBlock\Tag;
use Result;
use Request;
use Auth;
use DB;

class OptionController extends Controller
{
    //

    public $currentVersionAPI = 1;

    public $result = [];


    public function config()
    {

        $data['last_version'] = $this->lastVersionAPI;
        $data['minimum_version'] = $this->minimumVersionAPI;
        $data['current_version'] = $this->getCurrentVersion();
        $data['current_user'] = Auth::user()->only(['id', 'name', 'mobile', 'status', 'shop_expired_at', 'limit_insert_product', 'role']);

        return Result::setData($data)->get();
    }



    public function ip()
    {

        $data['ip'] = Request::ip();
        return Result::setData($data)->get();
    }

    public function updateCount()
    {

        #mainActivity ['count_product']
        if (User::where('id', 1)->update(['count_product' => Product::count()]))
            $this->result[] = 'تعداد محصولات ثبت شده در صفحه اضلی اپلیکیشن به روز رسانی شد';

        #Category ['count_like' , 'count_product']
        if ($this->updateCountProduct('categories', 'category_id'))
            $this->result[] = 'تعداد محصولات ثبت شده در هر مجله به روزرسانی شد';

        if ($this->updateCountLike('categories', Category::class))
            $this->result[] = 'تعداد لایک های ثبت شده برای هر مجله به روزرسانی شد';


        #like ['Product' , 'Post']
        if ($this->updateCountLike('posts', Post::class))
            $this->result[] = 'تعداد لایک های ثبت شده برای هر مقاله به روزرسانی شد';
        if ($this->updateCountLike('products', Product::class))
            $this->result[] = 'تعداد لایک های ثبت شده برای هر محصول به روزرسانی شد';


        #User ['count_like' , 'count_product']
        if ($this->updateCountProduct('users', 'user_id'))
            $this->result[] = 'تعداد محصولات درج شده توسط هر فرد به روزرسانی شد';
        if ($this->updateUserCountLike()) {
            $this->result[] = 'مجموع لایک های محصولات هر فرد به روزرسانی شد';
            $this->result[] = 'تعداد لایک های ثبت شده در صفحه اضلی اپلیکیشن به روز رسانی شد';
        } #This method should always be in the end


        foreach ($this->result as $result) {
            echo '<ul>';
            echo '<li>';
            echo $result;
            echo '</li>';
            echo '</ul>';
        }

    }


    private function updateCountProduct($table, $fk)
    {
        $ids = DB::table($table)->pluck('id');
        foreach ($ids as $id) {
            $countProduct = Product::where($fk, $id)->count();
            DB::table($table)->where('id', $id)->update(['count_product' => $countProduct]);

        }

        return true;
    }


    private function updateUserCountLike()
    {

        $userIds = User::pluck('id');

        foreach ($userIds as $userId) {
            $userCountLike = DB::table('products')
                ->leftJoin('users', 'users.id', '=', 'products.user_id')
                ->where('user_id', $userId)
                ->sum('products.count_like');

            DB::table('users')->where('id', $userId)->update(['count_like' => $userCountLike]);
        }


        # The following sentence should always be in the end
        ## mainActivity ['count_like']
        User::where('id', 1)->update(['count_like' => Likeable::count()]);

        return true;
    }


    private function updateCountLike($table, $class)
    {
        $ids = DB::table($table)->pluck('id');
        foreach ($ids as $id) {
            $countLike = Likeable::where('likeable_type', $class)->where('likeable_id', $id)->count();
            DB::table($table)->where('id', $id)->update(['count_like' => $countLike]);

        }
        return true;
    }


    private function getCurrentVersion()
    {
        $segment = request()->segment(2);

        $versionRegex = '/^v(?<zipCode>\d{1})$/';
        preg_match($versionRegex, $segment, $matches);

        return $matches['zipCode'];

    }





}
