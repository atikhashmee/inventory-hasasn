<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $start = date('Y-m-d 00:00:00');
        $end   = date('Y-m-d 23:59:59');
        $data = [];
        $data['total_sales_today'] = Order::whereBetween('created_at', [$start, $end])->sum('total_final_amount');
        $data['total_purchase_today'] = Stock::whereBetween('created_at', [$start, $end])->sum('price');
        $data['total_payment_today'] = Transaction::where('type', 'in')->whereNotNull('order_id')->where('flag', 'payment')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalDeposit = Transaction::where("type", "in")->whereNotNull('order_id')->whereBetween('created_at', [$start, $end])->groupBy('customer_id')->sum('amount');
        $totalWithdraw = Transaction::where("type", "out")->whereBetween('created_at', [$start, $end])->groupBy('customer_id')->sum('amount');
        $data['total_due_today'] = abs($totalDeposit - $totalWithdraw);
        $data['recent_sales'] = Order::orderBy('id', 'DESC')->limit(5)->get();
        $data['recent_purchase'] = Stock::orderBy('id', 'DESC')->limit(5)->get();
        $data['best_selling_products'] = Product::select('products.*', \DB::raw('IFNULL(A.top_products, 0) as totalCOunt'))
        ->leftJoin(\DB::raw('(SELECT count(product_id) as top_products, product_id FROM order_details GROUP BY product_id) as A'), 'A.product_id', '=', 'products.id')
        ->where('A.top_products', '>', 0)
        ->orderBy('totalCOunt', "DESC")
        ->limit(5)
        ->get();
        return view('home', $data);
    }

    public function userDashboard() {
        return view('user.home');
    }

    public function warentyCheck() {
        return view('admin.orders_more.warenty_check');
    }

    public function getWarentyCheckData(Request $request) {
        try {
            $order = Order::with('orderDetail')->where('order_number', $request->order_number)->first();
            $products = [];
            if ($order) {
                $details = $order->orderDetail;
                if (count($details) > 0) {
                    foreach ($details as $detail) {
                        $products[$detail->id] = [
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product_name,
                            'time_left' => 'Not Set'
                        ];
                        if ($detail->warenty_duration != null && intval($detail->warenty_duration) > 0) {
                            $effectiveDate = date('Y-m-d h:i:s a', strtotime("+".$detail->warenty_duration." months", strtotime($detail->created_at)));
                            $now = time(); // or your date as well
                            $your_date = strtotime($effectiveDate);
                            $datediff = $now - $your_date;
                            $products[$detail->id]['time_left'] = round($datediff / (60 * 60 * 24))." Days Left";
                        }
                    }
                }
                return response()->json(['status' => true, 'msg' => 'Data Found', 'data' => $products]);
            } else {
                return response()->json(['status' => false, 'error' => 'Data Not Found', 'data' => null]);  
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'data' => null]);  
        }
        
    }
}
