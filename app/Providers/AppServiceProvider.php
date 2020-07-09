<?php

namespace App\Providers;

use App\City;
use App\Models\GatewayTransaction;
use App\Role;
use App\Storyable;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        #todo  sh4: in count ha aslan dorost nist. bayad az samte front handle she. ya yek field dar db ijad she
//        $countProviderPlan = GatewayTransaction::
//        leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
//            ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
//            ->where('plans.type', 'BUY_PROVIDER_PLAN')
//            ->orWhere('plans.type', 'EXTEND_PROVIDER_PLAN')->count();
//
//        $countStarPlan = GatewayTransaction::
//        select(
//            'users.first_name as first_name', 'users.last_name as last_name',
//            'gateway_transactions.created_at', 'gateway_transactions.user_id', 'plans.day', 'plans.extra as star',
//            'gateway_transactions.related_id as product_id', 'gateway_transactions.status', 'gateway_transactions.id',
//            'gateway_transactions.price'
//        )
//            ->leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
//            ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
//            ->where('plans.type', 'BUY_PROVIDER_STAR')
//            ->count();
//
//        $countPromotePlan = GatewayTransaction::
//        select('users.first_name as first_name', 'users.last_name as last_name', 'products.title as product_title',
//            'gateway_transactions.created_at', 'gateway_transactions.user_id', 'plans.day',
//            'gateway_transactions.related_id as product_id', 'gateway_transactions.price', 'gateway_transactions.status', 'gateway_transactions.id'
//        )
//            ->leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
//            ->leftJoin('products', 'products.id', '=', 'gateway_transactions.related_id')
//            ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
//            ->where('plans.type', 'BUY_PROMOTE_PRODUCT')
//            ->count();


        $genders = [
            'male' => 'مرد',
            'female' => 'زن ',
        ];


        $cities = City::where('parent_id', '<>', null)->get();


        $roles = Role::all();

        View::share('genders', $genders);
        View::share('roles', $roles);
        View::share('cities', $cities);

        $countStory = Storyable::leftJoin('gateway_transactions', 'storyables.storyable_id', '=', 'gateway_transactions.related_id')
            ->leftJoin('plans', 'plans.id', '=', 'gateway_transactions.plan_id')
            ->leftJoin('users', 'users.id', '=', 'gateway_transactions.user_id')
            ->where('storyables.status',0)
            ->count();

        View::share('countStory', $countStory);


//        View::share('countProviderPlan', $countProviderPlan);
//        View::share('countStarPlan', $countStarPlan);
//        View::share('countPromotePlan', $countPromotePlan);

    }
}
