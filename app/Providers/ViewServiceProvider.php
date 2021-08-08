<?php

namespace App\Providers;

use App\Models\WareHouse;
use App\Models\Category;

use Illuminate\Support\ServiceProvider;
use View;

class ViewServiceProvider extends ServiceProvider
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
        View::composer(['products.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['products.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['products.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['products.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['products.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['products.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
       
    }
}