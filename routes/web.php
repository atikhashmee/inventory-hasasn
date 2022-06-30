<?php

use App\Models\OrderDetail;
use App\Models\Stock;
use App\Models\ShopInventory;
use App\Models\ShopProductStock;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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

function stockKahini($stock) {
    $warehouse_id = $stock->warehouse_id;
    $new_quantity = $stock->quantity;
    $product_id = $stock->product_id;
    $user_id    =$stock->user_id;
    $shop_id  = $stock->shop_id;
    $supplier_id = $stock->supplier_id;
    if ($stock->type == 'warehouse_transfer') {
        if (isset($new_quantity) && $new_quantity > 0) {
            $settle_quantity = $new_quantity; 
            $stocks = Stock::select('stocks.*', 'ST.total_stock_out', DB::raw('(stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))                  
            ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_product_stocks GROUP BY stock_id) as ST"), 'stocks.id', '=', 'ST.stock_id')
            ->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->having('stockQty', '>', 0)
            ->get();
            foreach ($stocks as $key => $st) {
                $checkQty = ($settle_quantity - $st->stockQty);
                if ($checkQty > 0) {
                    $stQty = $st->stockQty;
                } else {
                    $stQty = $settle_quantity;
                }
                $stOut = ShopProductStock::create([
                    'stock_id' => $st->id,
                    'warehouse_id' => $warehouse_id,
                    'user_id' => $user_id,
                    'shop_id' => $shop_id,
                    'supplier_id' => $st->supplier_id,
                    'product_id' => $product_id,
                    'quantity' => $stQty,
                    'type' => 'warehouse_transfer',
                    'price' => 0,
                ]);

                if ($stOut) {
                    $settle_quantity = $settle_quantity-$stOut->quantity;
                }

                if ($settle_quantity == 0) {
                    break;
                }
            }
        }
    } else if ($stock->type == 'user_transfer') {
        $stock = ShopProductStock::create([
            'user_id' => $user_id,
            'shop_id' => $shop_id, 
            'product_id' => $product_id,
            'supplier_id' => $supplier_id,
            'quantity' => $new_quantity,
            'type' => 'user_transfer',
            'price' => $stock->price
        ]);
    
    } else if ($stock->type == 'sale_return') {
  
        $shopStock = ShopProductStock::create([
            'user_id' => $user_id,
            'order_detail_id' => $stock->order_detail_id,
            'shop_id' => $shop_id,
            'product_id' => $product_id,
            'quantity' => $new_quantity,
            'type' => 'sale_return',
            'price' => $stock->price
        ]);
    } else if ($stock->type == 'shop_transfer') {

        $settle_quantity = $new_quantity;
        $stocks = ShopProductStock::select('shop_product_stocks.*', 'ST.total_stock_out', DB::raw('(shop_product_stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))
        ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_inventories GROUP BY stock_id) as ST"), 'shop_product_stocks.id', '=', 'ST.stock_id')
        ->where('shop_id', $stock->shop_from)
        ->where('product_id', $product_id)
        ->get();
        foreach ($stocks as $key => $st) {
            $checkQty = ($settle_quantity - $st->stockQty);
            if ($checkQty > 0) {
                $stQty = $st->stockQty;
            } else {
                $stQty = $settle_quantity;
            }

            $shop_stock = ShopProductStock::create([
                'user_id' => $user_id,
                'shop_id' => $shop_id, 
                'product_id' => $product_id,
                'supplier_id' => $st->supplier_id,
                'quantity' => $stQty,
                'type' => 'shop_transfer',
                'price' => 0,
            ]);
            if ($shop_stock) {
                $stOut =  ShopInventory::create([
                    'type' => 'shop_transfer',
                    'transfer_id' => $shop_stock->id,
                    'stock_id' => $st->id,
                    'product_id' => $st->product_id,
                    'shop_id' =>  $st->shop_id,
                    'quantity' =>  $stQty
                ]);
            }
            if ($stOut) {
                $settle_quantity = $settle_quantity-$stOut->quantity;
            }

            if ($settle_quantity == 0) {
                break;
            }
        }
    }
}

function orderKahini($order_detail) {
    $settle_quantity = $order_detail->final_quantity;
    if ($order_detail) {
        $stocks = ShopProductStock::select('shop_product_stocks.*', 'ST.total_stock_out', DB::raw('(shop_product_stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))
        ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_inventories GROUP BY stock_id) as ST"), 'shop_product_stocks.id', '=', 'ST.stock_id')
        ->where('shop_id', $order_detail->shop_id)
        ->where('product_id', $order_detail->product_id)
        ->having('stockQty', '>', 0)
        ->get();

        foreach ($stocks as $key => $st) {
            $checkQty = ($settle_quantity - $st->stockQty);
            if ($checkQty > 0) {
                $stQty = $st->stockQty;
                } else {
                    $stQty = $settle_quantity;
                }
                $stOut =  ShopInventory::create([
                    'type' => 'order_placed',
                    'order_detail_id' => $order_detail->id,
                    'stock_id' => $st->id,
                    'product_id' => $st->product_id,
                    'shop_id' =>  $st->shop_id,
                    'quantity' =>  $stQty
                ]);

                if ($stOut) {
                    $settle_quantity = $settle_quantity-$stOut->quantity;
                }

                if ($settle_quantity == 0) {
                    return;
                }
        }
    }
}

Route::get('/migrate-data-warehouse', function() {
    $stockData = \DB::table('shop_product_stocks_copy')
    ->select('shop_product_stocks_copy.*', 'shop_inventories_copy.shop_id as shop_from')
    ->leftJoin('shop_inventories_copy', 'shop_inventories_copy.transfer_id', '=', 'shop_product_stocks_copy.id')
    ->orderBy('shop_product_stocks_copy.id', 'ASC')
    ->get();
    $order_details = OrderDetail::where('returned_quantity', '=', 0)->get()->toArray();
    $dataArr = [];
    foreach ($stockData as $key => $stock) {
        $dataArr[date('Y-m-d H:i:s', strtotime($stock->created_at))][] =  $stock;
    }
    foreach ($order_details as $key => $order_detail) {
        $dataArr[date('Y-m-d H:i:s', strtotime($order_detail['created_at']))][] = (object)$order_detail;
    }
    foreach ($dataArr as $dateTime => $arr) {
        foreach ($arr as $key => $value) {
            if (isset($value->order_id)) {
                orderKahini($value);
            } else {
                stockKahini($value);
            }
        }
    }
    
    dd($stockData);
});

// universal routes
Route::get('print-invoice/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printInvoice']);
Route::get('print-challan/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printChallan']);
Route::get('print-warenty-serials/{order_id}', [App\Http\Controllers\InvoiceController::class, 'printWarentySerails']);
Route::get('print-challan-conditioned/{challan_id}', [App\Http\Controllers\InvoiceController::class, 'printChallanCondition']);
Route::get('print-quotation/{quotation_id}', [App\Http\Controllers\InvoiceController::class, 'printQuotation']);
Route::get('shop_stock_products/{shop_id}', [App\Http\Controllers\OrderController::class, 'getProductsByShop']);
Route::get('get-customers', [App\Http\Controllers\CustomerController::class, 'getAllCustomerJson'])->name('getCustomers');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.'], function () {
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