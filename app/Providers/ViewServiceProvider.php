<?php

namespace App\Providers;
use App\Models\Product;


use App\Models\Menufacture;
use App\Models\Supplier;
use App\Models\Brand;

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
        View::composer(['stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['stocks.fields'], function ($view) {
            $productItems = Product::pluck('name','id')->toArray();
            $view->with('productItems', $productItems);
        });
        View::composer(['products.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['products.fields'], function ($view) {
            $menufactureItems = Menufacture::pluck('name','id')->toArray();
            $view->with('menufactureItems', $menufactureItems);
        });
        View::composer(['products.fields'], function ($view) {
            $supplierItems = Supplier::pluck('name','id')->toArray();
            $view->with('supplierItems', $supplierItems);
        });
        View::composer(['products.fields'], function ($view) {
            $brandItems = Brand::pluck('name','id')->toArray();
            $view->with('brandItems', $brandItems);
        });
        View::composer(['products.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        
    
    }
}