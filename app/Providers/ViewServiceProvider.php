<?php

namespace App\Providers;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Country;
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
        View::composer(['admin.challans.fields'], function ($view) {
            $unitItems = Unit::pluck('name','id')->toArray();
            $view->with('unitItems', $unitItems);
        });
        View::composer(['admin.challans.fields'], function ($view) {
            $customerItems = Customer::pluck('customer_name','id')->toArray();
            $view->with('customerItems', $customerItems);
        });
        View::composer(['admin.products.fields'], function ($view) {
            $menufactureItems = Menufacture::pluck('name','id')->toArray();
            $view->with('menufactureItems', $menufactureItems);
        });
        View::composer(['admin.products.fields'], function ($view) {
            $brandItems = Brand::pluck('name','id')->toArray();
            $view->with('brandItems', $brandItems);
        });
        View::composer(['admin.products.fields'], function ($view) {
            $countryItems = Country::pluck('name','id')->toArray();
            $view->with('countryItems', $countryItems);
        });
        View::composer(['admin.products.fields'], function ($view) {
            $categoryItems = Category::with('nested', 'nested.nested')->where('parent_id', 0)->get()->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $supplierItems = Supplier::pluck('name','id')->toArray();
            $view->with('supplierItems', $supplierItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $productItems = Product::pluck('name','id')->toArray();
            $view->with('productItems', $productItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $supplierItems = Supplier::pluck('name','id')->toArray();
            $view->with('supplierItems', $supplierItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $productItems = Product::pluck('name','id')->toArray();
            $view->with('productItems', $productItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $supplierItems = Supplier::pluck('name','id')->toArray();
            $view->with('supplierItems', $supplierItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $productItems = Product::pluck('name','id')->toArray();
            $view->with('productItems', $productItems);
        });
        View::composer(['admin.suppliers.fields'], function ($view) {
            $countryItems = Country::pluck('name','id')->toArray();
            $view->with('countryItems', $countryItems);
        });
        View::composer(['admin.suppliers.fields'], function ($view) {
            $countryItems = Country::pluck('name','id')->toArray();
            $view->with('countryItems', $countryItems);
        });
        View::composer(['admin.categories.fields'], function ($view) {
            $categoryItems = Category::with('nested')->where('parent_id', 0)->get();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['admin.stocks.fields'], function ($view) {
            $productItems = Product::pluck('name','id')->toArray();
            $view->with('productItems', $productItems);
        });
        
    }
}