<?php

namespace App\Providers;


use App\Models\Shop;
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
        View::composer(['admin.quotations.fields'], function ($view) {
            $shopItems = Shop::pluck('name','id')->toArray();
            $unitItems = Unit::pluck('name','id')->toArray();
            array_unshift($unitItems, 'Unit');
            $view->with('shopItems', $shopItems)->with('unitItems', $unitItems);
        });
        View::composer(['admin.challans.fields', 'user.challans.fields'], function ($view) {
            $challan_types = ['Condition With Charge' => 'Condition With Charge', 'Condition Only' => 'Condition Only', 'Charge Only' => 'Charge Only' ];
            $view->with('challan_types', $challan_types);
        });
        View::composer(['admin.challans.fields', 'user.challans.fields'], function ($view) {
            $unitItems = Unit::pluck('name','id')->toArray();
            $view->with('unitItems', $unitItems);
        });
        View::composer(['admin.challans.fields', 'user.challans.fields'], function ($view) {
            $customerItems = Customer::pluck('customer_name','id')->toArray();
            $view->with('customerItems', $customerItems);
        });
        View::composer(['admin.challans.fields', 'user.challans.fields'], function ($view) {
            $shopItems = Shop::pluck('name','id')->toArray();
            $view->with('shopItems', $shopItems);
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
        View::composer(['admin.stocks.fields', 'user.stocks.fields'], function ($view) {
            $ware_houseItems = WareHouse::pluck('ware_house_name','id')->toArray();
            $view->with('ware_houseItems', $ware_houseItems);
        });
        View::composer(['admin.stocks.fields', 'user.stocks.fields'], function ($view) {
            $supplierItems = Supplier::pluck('name','id')->toArray();
            $view->with('supplierItems', $supplierItems);
        });

        View::composer(['admin.suppliers.fields'], function ($view) {
            $countryItems = Country::pluck('name','id')->toArray();
            $view->with('countryItems', $countryItems);
        });
        View::composer(['admin.categories.fields'], function ($view) {
            $categoryItems = Category::with('nested')->where('parent_id', 0)->get();
            $view->with('categoryItems', $categoryItems);
        });

        View::composer(['layouts.sidebar'], function ($view) {
            $user = auth()->user();
            $shop_logo = '';
            if ($user->role != 'admin') {
                $shop = $user->shop;
                if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
                    $shop_logo = $shop->image_link = asset('/uploads/shops/'.$shop->image);
                } else {
                    $shop_logo =  $shop->image_link = asset('assets/img/not-found.png');
                }
            }
            $view->with('shop_logo', $shop_logo);
        });
        
    }
}