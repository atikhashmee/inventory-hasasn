<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\WarentySerial;
use Doctrine\DBAL\Schema\View;
use App\Models\ShopProductStock;
use App\Models\Brand;

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
        $first_day_this_month = date('m-01-Y 00:00:00'); // hard-coded '01' for first day
        $last_day_this_month  = date('m-t-Y 23:59:59');
        $user = auth()->user();
        $data = [];
        
        $data['total_payment_today'] = Transaction::where('type', 'in')->whereNotNull('order_id')->where('flag', 'payment')->whereBetween('created_at', [$start, $end])->sum('amount');
        $totalDeposit = Transaction::where("type", "in")->whereNotNull('order_id')->whereBetween('created_at', [$start, $end])->groupBy('customer_id')->sum('amount');
        $totalWithdraw = Transaction::where("type", "out")->whereBetween('created_at', [$start, $end])->groupBy('customer_id')->sum('amount');
        $data['total_due_today'] = abs($totalDeposit - $totalWithdraw);
        if ($user->role == 'admin') {
            $data['total_purchase_today'] = Stock::whereBetween('created_at', [$start, $end])->sum('price');
            $data['recent_purchase'] = Stock::orderBy('id', 'DESC')->limit(5)->get();
            $data['recent_sales'] = Order::orderBy('id', 'DESC')->limit(5)->get();
            $data['total_sales_today'] = Order::whereBetween('created_at', [$start, $end])->sum('total_final_amount');
            $data['total_refunds_today'] = Transaction::where('type', 'out')->whereNotNull('order_id')->where('flag', 'refund')->whereBetween('created_at', [$start, $end])->sum('amount');
            $data['total_regular_sales'] = Order::where('order_challan_type', 'walk-in')->whereBetween('created_at', [$first_day_this_month, $last_day_this_month])->sum('total_final_amount');
            $data['total_condition_sales'] = Order::where('order_challan_type', 'challan')->whereBetween('created_at', [$first_day_this_month, $last_day_this_month])->sum('total_final_amount');
        } else {
            $data['recent_purchase'] = ShopProductStock::where('type', 'user_transfer')->orderBy('id', 'DESC')->limit(5)->get();
            $data['recent_sales'] = Order::where('shop_id', $user->shop_id)->orderBy('id', 'DESC')->limit(5)->get();
            $data['total_sales_today'] = Order::where('shop_id', $user->shop_id)->whereBetween('created_at', [$start, $end])->sum('total_final_amount');
            $data['total_refunds_today'] = Transaction::where('transactions.type', 'out')
            ->join('users', 'users.id', '=', 'transactions.user_id') 
            ->where('users.shop_id', $user->shop_id)->whereNotNull('transactions.order_id')->where('transactions.flag', 'refund')->whereBetween('transactions.created_at', [$start, $end])->sum('amount');
            $data['total_regular_sales'] = Order::where('shop_id', $user->shop_id)->where('order_challan_type', 'walk-in')->sum('total_final_amount');
            $data['total_condition_sales'] = Order::where('shop_id', $user->shop_id)->where('order_challan_type', 'challan')->sum('total_final_amount');
            $data['total_purchase_today'] = ShopProductStock::where('shop_id', $user->shop_id)->where('type', 'user_transfer')->whereBetween('created_at', [$start, $end])->sum('price');
        }
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

            if ( $request->order_number == null ||  $request->order_number == '') {
                return response()->json(['status' => false, 'error' => 'Enter Order or Serial Number', 'data' => null]); 
            }
            if ($request->search_type == 'ON') {
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
            } else if ($request->search_type == 'SN') {
                $warenty = WarentySerial::select('warenty_serials.order_detail_id', 'order_details.product_id', 'order_details.product_name', 'order_details.warenty_duration', 'order_details.created_at')
                ->leftJoin('order_details', 'order_details.id', '=', 'warenty_serials.order_detail_id')
                ->where('warenty_serials.serial_number', $request->order_number)
                ->first();
                $products = [];
                if ($warenty) {
                    $products[$warenty->order_detail_id] = [
                        'product_id' => $warenty->product_id,
                        'product_name' => $warenty->product_name,
                        'time_left' => 'Not Set'
                    ];
                    if ($warenty->warenty_duration != null && intval($warenty->warenty_duration) > 0) {
                        $effectiveDate = date('Y-m-d h:i:s a', strtotime("+".$warenty->warenty_duration." months", strtotime($warenty->created_at)));
                        $now = time(); // or your date as well
                        $your_date = strtotime($effectiveDate);
                        $datediff = $now - $your_date;
                        $products[$warenty->order_detail_id]['time_left'] = round($datediff / (60 * 60 * 24))." Days Left";
                    }
                    return response()->json(['status' => true, 'msg' => 'Data Found', 'data' => $products]);
                } else {
                    return response()->json(['status' => false, 'error' => 'Data Not Found', 'data' => null]);  
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'data' => null]);  
        }
        
    }


    public function getTopSellingProducts(Request $request) {
        $data["brands"] = Brand::get();
        $best_selling_products_sql = Product::select('products.*', \DB::raw('IFNULL(A.top_products, 0) as totalCOunt'));
        if ($request->start !='' && $request->end !='') {
            $startDate = date($request->start. ' 00:00:00');
            $endDate = date($request->end. ' 23:59:59');
            $best_selling_products_sql->leftJoin(\DB::raw('(SELECT count(product_id) as top_products, product_id FROM order_details WHERE created_at BETWEEN "'.$startDate.'" AND "'.$endDate.'" GROUP BY product_id) as A'), 'A.product_id', '=', 'products.id');
        } else {
            $best_selling_products_sql->leftJoin(\DB::raw('(SELECT count(product_id) as top_products, product_id FROM order_details GROUP BY product_id) as A'), 'A.product_id', '=', 'products.id');
        }
        $best_selling_products_sql->where(function($q) use($request) {
            if ($request->brand_id) {
                $q->where('brand_id', $request->brand_id);
            } 
        })
        ->where('A.top_products', '>', 0)
        ->orderBy('totalCOunt', "DESC");
        $data['best_selling_products'] = $best_selling_products_sql->paginate(100);
        return view('top-selling-products', $data);
    }
}
