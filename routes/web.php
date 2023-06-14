<?php

use App\Models\Order;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\ShopInventory;
use App\Models\ShopProductStock;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use Illuminate\Routing\Route as RoutingRoute;
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
Route::get('command', function () {
    \Artisan::call('cache:forget spatie.permission.cache');
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    dd("All clear!");
});
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get("/invoice-no", function() {
    $orders = Order::select(\DB::raw('DATE(created_at) as created_at'))->groupBy(\DB::raw('DATE(created_at)'))->get();
    if (count($orders) > 0) {
        foreach ($orders as $order) {
            $dOrders = Order::where(\DB::raw("DATE(created_at)"), date('Y-m-d', strtotime($order->created_at)))->orderBy("created_at", "ASC")->get();
            if (count($dOrders) > 0) {
                $counter = 0;
                foreach ($dOrders as $key => $order) {
                    $key++;
                    $invoiceNo = date("ymd", strtotime($order->created_at))."-";
                    $invoiceNo .= str_pad($key, 4, "0", STR_PAD_LEFT);
                    $order->invoice_no = $invoiceNo; 
                    $order->save();
                }
            }
        }
    }
});

Route::get("adjust-customer-balance/{customerId}", [CustomerController::class, 'adjustBalance'])->name("adjustBal");


// universal routes
Route::get('print-invoice/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printInvoice']);
Route::get('print-challan/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printChallan']);
Route::get('print-warenty-serials/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printWarentySerails']);
Route::get('print-challan-conditioned/{challan_id}', [App\Http\Controllers\InvoiceController::class, 'printChallanCondition']);
Route::get('print-quotation/{quotation_id}', [App\Http\Controllers\InvoiceController::class, 'printQuotation']);
Route::get('shop_stock_products/{shop_id}', [App\Http\Controllers\OrderController::class, 'getProductsByShop']);
Route::get('get-customers', [App\Http\Controllers\CustomerController::class, 'getAllCustomerJson'])->name('getCustomers');
Route::get('get-products', [App\Http\Controllers\ProductController::class, 'getAllProductsSearchJson'])->name('getProducts');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/all-customers-outstanding', [App\Http\Controllers\TransactionController::class, 'getCustomersOutstandingLists'])->name('outstandingCustomers');
    Route::get('/top-selling-products', [App\Http\Controllers\HomeController::class, 'getTopSellingProducts'])->name('topSellingProducts');
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
    Route::post('orders/drafts',  [App\Http\Controllers\OrderController::class, 'deleteDraftOrders'])->name('order.draft.delete');
    Route::get('orders/drafts',  [App\Http\Controllers\OrderController::class, 'getDraftOrders'])->name('order.draft.index');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::resource('transactions', App\Http\Controllers\TransactionController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);
    Route::resource('challans', App\Http\Controllers\ChallanController::class);
    Route::resource('quotations', App\Http\Controllers\QuotationController::class);


    Route::post('shop_products/update-shop-to-shop',[App\Http\Controllers\ShopProductController::class, 'updateShopToShopProduct'])->name('shop_products.updateshoptoshop');
    Route::get('shop_products/get-resource',[App\Http\Controllers\ShopProductController::class, 'getResource']);
    Route::get('get_product_detail/{id}', [App\Http\Controllers\ProductController::class, 'getProductJson'])->name('getProduct.individual');
    Route::get('get_shop_products/{shop_id}', [App\Http\Controllers\ShopProductController::class, 'getShopProducts'])->name('getShop_products');
    Route::resource('shop_products', App\Http\Controllers\ShopProductController::class);
    Route::put('order/sell-return-rollback',  [App\Http\Controllers\OrderController::class, 'salesReturnRollback'])->name('order.return.rollback');
    Route::post('order/sell-return-update',  [App\Http\Controllers\OrderController::class, 'salesReturnUpdate'])->name('order.return.update');
    Route::get('order/sell-return',  [App\Http\Controllers\OrderController::class, 'showReturnLists'])->name('order.return');
    Route::get('order/sell-return/create',  [App\Http\Controllers\OrderController::class, 'salesReturnForm'])->name('order.return.create');
    Route::get('order-resource',  [App\Http\Controllers\OrderController::class, 'getResources'])->name('order.getResource');
    Route::get('warenty-check',  [App\Http\Controllers\HomeController::class, 'warentyCheck'])->name('warenty.check');
    Route::post('submit-warenty-serial-number',  [App\Http\Controllers\OrderController::class, 'submitWarentlySerial'])->name('submit.warenty.serial');
    Route::get('set-warenty-serial-number/{order_id}',  [App\Http\Controllers\OrderController::class, 'setWarentySerial'])->name('set.warenty.serial');
    Route::post('warenty-check-validation',  [App\Http\Controllers\HomeController::class, 'getWarentyCheckData'])->name('warenty.check.validation');
    
    Route::group(['prefix' => 'report', 'as' => 'report.'], function() {
        Route::get('sells', [App\Http\Controllers\ReportController::class, 'index'])->name('sells');
        Route::get('sells-detail', [App\Http\Controllers\ReportController::class, 'salesDetail'])->name('sells_detail');
        Route::get('purchase-detail', [App\Http\Controllers\ReportController::class, 'purchaseDetail'])->name('purchase_detail');
        Route::get('purchase', [App\Http\Controllers\ReportController::class, 'purchaseReport'])->name('purchase');
        Route::get('payment', [App\Http\Controllers\ReportController::class, 'paymentReport'])->name('payment');
        Route::get('profit-loss', [App\Http\Controllers\ReportController::class, 'profitLoss'])->name('profitloss');
        Route::get('product-sale-history', [App\Http\Controllers\ReportController::class, 'productWiseSaleHistory'])->name('productWiseSaleHistory');
    });
});