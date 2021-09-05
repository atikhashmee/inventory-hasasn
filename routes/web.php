<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Route as ComponentRoutingRoute;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('test-pdf', function(){
    $html = '<h1>Bill</h1><p>You owe me money, dude.</p>';
    return PDF::loadFile(url('pdf-url'))->inline('github.pdf');
    return PDF::loadHTML($html)->inline('github.pdf');

    PDF::loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->save('myfile.pdf');


});
Route::get('pdf-url', function() {
    return view('pdf-layout');
});
// universal routes
Route::get('shop_stock_products/{shop_id}', [App\Http\Controllers\OrderController::class, 'getProductsByShop']);
Route::get('get-customers', [App\Http\Controllers\CustomerController::class, 'getAllCustomerJson'])->name('getCustomers');
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware('auth');
    Route::resource('wareHouses', App\Http\Controllers\WareHouseController::class);
    Route::resource('shops', App\Http\Controllers\ShopController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('menufactures', App\Http\Controllers\MenufactureController::class);
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('brands', App\Http\Controllers\BrandController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('stocks', App\Http\Controllers\StockController::class);
    Route::resource('orders', App\Http\Controllers\OrderController::class);


    Route::post('shop_products/update-shop-to-shop',[App\Http\Controllers\ShopProductController::class, 'updateShopToShopProduct'])->name('shop_products.updateshoptoshop');
    Route::get('shop_products/get-resource',[App\Http\Controllers\ShopProductController::class, 'getResource']);
    Route::get('get_product_detail/{id}', [App\Http\Controllers\ProductController::class, 'getProductJson'])->name('getProduct.individual');
    Route::get('get_shop_products/{shop_id}', [App\Http\Controllers\ShopProductController::class, 'getShopProducts'])->name('getShop_products');
    Route::resource('shop_products', App\Http\Controllers\ShopProductController::class);


    Route::get('order-resource',  [App\Http\Controllers\OrderController::class, 'getResources'])->name('order.getResource');
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'userDashboard'])->name('home');
    Route::get('/new_order', [App\Http\Controllers\OrderController::class, 'userOrderCreate'])->name('new_order');
});







