<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Supplier;
use Laracasts\Flash\Flash;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ShopProductStock;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        try {
            $year = $request->year ?? date('Y');
            $sql = Order::select(\DB::raw('COUNT(id) AS countId'),\DB::raw('SUM(total_final_amount) AS charges'),\DB::raw('DATE(created_at) AS date'))
                ->where(function($q) use($request) {
                    if (!empty($request->shop_id)) {
                        $q->where('shop_id', $request->shop_id);
                    }
                    if (!empty($request->customer_id)) {
                        $q->where('customer_id', $request->customer_id);
                    }
                });
                if ($user->role != 'admin') {
                    $sql->where('shop_id', $user->shop_id);
                }
                $sql->whereYear('created_at', $year)
                ->groupBy(\DB::raw('DATE(created_at)'));
           

            $items = $sql->get();
            $data = [];
            foreach ($items as $item) {
                $dateArr = explode('-', $item->date);
                if (!$request->query('show') || $request->query('show') == 'charge') {
                    $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->charges;
                } else {
                    $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->countId;
                }
            }

            if ($user->role == 'admin') {
                $customers = Customer::get();
                $shops = Shop::get();
            } else {
                $customers = Customer::where('shop_id', $user->shop_id)->get();
                $shops = [];
            }

            $data['data'] = $data;
            $data['customers'] = $customers;
            $data['shops'] = $shops;
            $data['user'] = $user;
            return view('admin.reports.sales', $data);

        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function salesDetail(Request $request)
    {
        $user = auth()->user();
        $orders = Order::whereDate('created_at', $request->date)->get();
        return view('admin.reports.sales_detail', compact('orders'));
    }

    public function purchaseDetail(Request $request)
    {
        $user = auth()->user();
        $stocks = Stock::whereDate('created_at', $request->date)->get();
        return view('admin.reports.purchase_detail', compact('stocks'));
    }

    public function purchaseReport(Request $request)
    {
        $user = auth()->user();
        try {
            $year = $request->year ?? date('Y');

                if ($user->role == 'admin') {
                        $sql = Stock::select(
                            \DB::raw('COUNT(id) AS countId'),
                            \DB::raw('SUM(price) AS totalPrice'),
                            \DB::raw('DATE(created_at) AS date')
                        );
                } else {
                    $sql = ShopProductStock::select(
                        \DB::raw('COUNT(id) AS countId'),
                        \DB::raw('SUM(price) AS totalPrice'),
                        \DB::raw('DATE(created_at) AS date')
                    )
                    ->where('type', 'user_transfer')
                    ->where('shop_id', $user->shop_id);
                }
               

                    $sql->where(function($q) use($request) {
                        if (!empty($request->supplier_id)) {
                            $q->where('supplier_id', $request->supplier_id);
                        }
                        if (!empty($request->product_id)) {
                            $q->where('product_id', $request->product_id);
                        }
                    })
                    ->whereYear('created_at', $year)
                    ->groupBy(\DB::raw('DATE(created_at)'));
           

            $items = $sql->get();
            $data = [];
            foreach ($items as $item) {
                $dateArr = explode('-', $item->date);
                $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->totalPrice;
                // if (!$request->query('show') || $request->query('show') == 'charge') {
                    //     $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->charges;
                // } else {
                //     $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->countId;
                // }
            }
            $products = Product::get();
            $suppliers = Supplier::get();
            return view('admin.reports.purchase', compact('data', 'products', 'suppliers'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function paymentReport(Request $request)
    {
        
        $user = auth()->user();
        try {
            $year = $request->year ?? date('Y');
                $sql = Transaction::select(
                        \DB::raw('COUNT(id) AS countId'),
                        \DB::raw('SUM(amount) AS amounts'),
                        \DB::raw('DATE(created_at) AS date')
                    )
                    ->where(function($q) use($request) {
                        if (!empty($request->customer_id)) {
                            $q->where('customer_id', $request->customer_id);
                        }
                    })
                    ->whereYear('created_at', $year)
                    ->groupBy(\DB::raw('DATE(created_at)'));
           

            $items = $sql->get();
            $data = [];
            foreach ($items as $item) {
                $dateArr = explode('-', $item->date);
                $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->amounts;
                // if (!$request->query('show') || $request->query('show') == 'charge') {
                //     $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->charges;
                // } else {
                //     $data[intVal($dateArr[1])][intVal($dateArr[2])] = $item->countId;
                // }
            }
            $customers = Customer::get();
            return view('admin.reports.payment', compact('data', 'customers'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function profitLoss(Request $request)
    {
        
        $user = auth()->user();
        try {
            $year = $request->year ?? date('Y');
            $orderSql = Order::select(
                    \DB::raw('GROUP_CONCAT(IFNULL(ORD.total_purchase, 0)-IFNULL(ORD.total_sells,0)) as amount_final'),
                    \DB::raw('COUNT(id) AS countId'),
                    \DB::raw('SUM(total_final_amount) AS charges'),
                    \DB::raw('DATE(created_at) AS date')
                )
                ->leftJoin(\DB::raw("(SELECT SUM(product_original_unit_price * final_quantity) as total_purchase, SUM(product_unit_price * final_quantity) as total_sells, order_id FROM order_details GROUP BY order_id) as ORD"), 'ORD.order_id', '=', 'orders.id')
                ->whereYear('orders.created_at', $year)
                ->groupBy(\DB::raw('DATE(orders.created_at)'));
            $sells = $orderSql->get();
            $data = [];
            foreach ($sells as $item) {
                $dateArr = explode('-', $item->date);
                $daySUm = 0;
                $allData = explode(',', $item->amount_final);
                if (count($allData) > 0) {
                    foreach ($allData as $eachsale) {
                        if (strpos($eachsale, '-') !== false) {
                            list($purchaseAmount, $salesAmount) = explode('-', $eachsale);
                            $daySUm += intval($purchaseAmount) +  intval($salesAmount);
                        }
                    }
                }
                $data[intVal($dateArr[1])][intVal($dateArr[2])] = $daySUm;
            }
            return view('admin.reports.profit_loss', compact('data'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function productWiseSaleHistory(Request $request) {
        $user = auth()->user();
        $data['orders'] = OrderDetail::select('order_details.*')
        ->with(['order', 'shop', 'order.customer'])
        ->where(function($q) {

            if (request()->query('start')!='' && request()->query('end')!='') {
                $q->whereBetween(\DB::raw('DATE(created_at)'), [request()->query('start'),  request()->query('end')]);
            }

            if (request()->query('search')!='') {
                $q->orWhereHas('order', function($r) {
                    $r->where('order_number', 'LIKE', '%'.request()->query('search').'%');
                });

                $q->orWhereHas('order.customer', function($r) {
                    $r->where('customer_name', 'LIKE', '%'.request()->query('search').'%');
                });
            }

            if (request()->query('product_id')!='') {
                $q->where('product_id', request()->query('product_id'));
            }

            if (request()->query('shop_id')!='') {
                $q->where('shop_id', request()->query('shop_id'));
            }

            if (request()->query('customer_id')!='') {
                $q->orWhereHas('order', function($r) {
                    $r->where('customer_id', request()->query('customer_id'));
                });
               
            }
        })
        ->paginate(100);
        $data['serial'] = pagiSerial($data['orders'], 100);
        if ($user->role == 'admin') {
            $data['shops']  = Shop::get();
            $data['customers'] = Customer::get();
        } else {
            $data['customers'] = Customer::where('user_id', $user->id)->get();
        }
        $data['user'] = $user;
        $data['products'] = Product::get();
        return view('admin.reports.product-sale-history', $data);
    }
}
