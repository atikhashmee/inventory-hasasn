<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Laracasts\Flash\Flash;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
                })
                ->whereYear('created_at', $year)
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
            $shops = Shop::get();
            $customers = Customer::get();
            
            if ($user->role == 'admin') {
                return view('admin.reports.sales', compact('data', 'customers', 'shops'));
            } else {
                return view('user.reports.sales', compact('data', 'customers', 'shops'));
            }

        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function salesDetail(Request $request)
    {
        $user = auth()->user();
        $orders = Order::whereDate('created_at', $request->date)->get();
        if ($user->role == 'admin') {
            return view('admin.reports.sales_detail', compact('orders'));
        } else {
            return view('user.reports.sales_detail', compact('orders'));
        }
    }

    public function purchaseDetail(Request $request)
    {
        $user = auth()->user();
        $stocks = Stock::whereDate('created_at', $request->date)->get();
        if ($user->role == 'admin') {
            return view('admin.reports.purchase_detail', compact('stocks'));
        } else {
            return view('user.reports.purchase_detail', compact('stocks'));
        }
    }

    public function purchaseReport(Request $request)
    {
        $user = auth()->user();
        try {
            $year = $request->year ?? date('Y');
                $sql = Stock::select(
                        \DB::raw('COUNT(id) AS countId'),
                        \DB::raw('SUM(price) AS totalPrice'),
                        \DB::raw('DATE(created_at) AS date')
                    )
                    ->where(function($q) use($request) {
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
            if ($user->role == 'admin') {
                return view('admin.reports.purchase', compact('data', 'products', 'suppliers'));
            } else {
                return view('user.reports.purchase', compact('data', 'products', 'suppliers'));
            }
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
            if ($user->role == 'admin') {
                return view('admin.reports.payment', compact('data', 'customers'));
            } else {
                return view('user.reports.payment', compact('data', 'customers'));
            }
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
            if ($user->role == 'admin') {
                return view('admin.reports.profit_loss', compact('data'));
            } else {
                return view('user.reports.profit_loss', compact('data'));
            }
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }
}
