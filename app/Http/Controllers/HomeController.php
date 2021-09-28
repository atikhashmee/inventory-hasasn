<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
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
        $data['total_due_today'] = 0;
        $data['recent_sales'] = Order::orderBy('id', 'DESC')->limit(5)->get();
        $data['recent_purchase'] = Stock::orderBy('id', 'DESC')->limit(5)->get();
        $data['best_selling_products'] = Product::select('products.*', \DB::raw('IFNULL(A.top_products, 0) as totalCOunt'))
        ->leftJoin(\DB::raw('(SELECT count(product_id) as top_products, product_id FROM order_details GROUP BY product_id) as A'), 'A.product_id', '=', 'products.id')
        ->orderBy('totalCOunt', "DESC")
        ->limit(5)
        ->get();
        return view('home', $data);
    }

    public function userDashboard() {
        return view('user.home');
    }
}
