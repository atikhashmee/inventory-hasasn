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
            return view('admin.reports.sales', compact('data', 'customers', 'shops'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function salesDetail(Request $request)
    {
        $orders = Order::whereDate('created_at', $request->date)->get();
        return view('admin.reports.sales_detail', compact('orders'));
    }

    public function purchaseDetail(Request $request)
    {
        $stocks = Stock::whereDate('created_at', $request->date)->get();
        
        return view('admin.reports.sales_detail', compact('stocks'));
    }

    public function purchaseReport(Request $request)
    {
        try {
            $year = $request->year ?? date('Y');
                $sql = Stock::select(
                        \DB::raw('COUNT(id) AS countId'),
                        \DB::raw('SUM(price) AS totalPrice'),
                        \DB::raw('DATE(created_at) AS date')
                    )
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
        try {
            $year = $request->year ?? date('Y');
                $sql = Transaction::select(
                        \DB::raw('COUNT(id) AS countId'),
                        \DB::raw('SUM(amount) AS amounts'),
                        \DB::raw('DATE(created_at) AS date')
                    )
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
        try {
            $year = $request->year ?? date('Y');
            $year = $request->year ?? date('Y');
            $orderSql = Order::select(
                    \DB::raw('COUNT(id) AS countId'),
                    \DB::raw('SUM(total_final_amount) AS charges'),
                    \DB::raw('DATE(created_at) AS date')
                )
                ->whereYear('created_at', $year)
                ->groupBy(\DB::raw('DATE(created_at)'));
            $sells = $orderSql->get();

            $purchaseSql = Stock::select(
                \DB::raw('COUNT(id) AS countId'),
                \DB::raw('SUM(price) AS totalPrice'),
                \DB::raw('DATE(created_at) AS date')
            )
            ->whereYear('created_at', $year)
            ->groupBy(\DB::raw('DATE(created_at)'));
            $purchases = $purchaseSql->get();
            $data = [];
            foreach ($sells as $item) {
                $dateArr = explode('-', $item->date);
                $data[intVal($dateArr[1])][intVal($dateArr[2])]['sell'] = $item->charges;
            }
            
            foreach ($purchases as $items) {
                $dateArr = explode('-', $items->date);
                $data[intVal($dateArr[1])][intVal($dateArr[2])]['buy'] = $items->totalPrice;
            }
            return view('admin.reports.profit_loss', compact('data'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }
}
