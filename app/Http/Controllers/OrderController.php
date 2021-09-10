<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $data['orders'] = Order::where(function($q){
            if (request()->query('search')!='') {
                $q->where('order_number', 'LIKE', '%'.request()->query('search').'%');
                $q->orWhereHas('customer', function($r) {
                    $r->where('customer_name', 'LIKE', '%'.request()->query('search').'%');
                });
            }

            if (request()->query('order_ids')!='') {
                $q->whereIn('id', explode(',', request()->query('order_ids')));
            }
            if (request()->query('shop_id')!='') {
                $q->where('shop_id', request()->query('shop_id'));
            }
        })->orderBy('id', 'DESC')->paginate(100);
        $data['shops'] = Shop::get();
        return view('admin.orders.index', $data);
    }

    public function create() {
        $data['shops'] = Shop::where('status', 'active')->get();
        $data['units'] = Unit::select('id', 'name', 'quantity_base')->where('status', 'active')->get();
        return view('admin.orders.create', $data);
    }

    public function userOrderCreate() {
        return view('user.new_order');
    }

    public function getResources() {
        try {
            $data = [];
            $data['products'] = Product::get();
            return response()->json(['status'=>true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
        return $data;
    }

    public function store(Request $request) {
        try {
            \DB::beginTransaction();
            $data = $request->all();
            $customer = Customer::updateOrCreate([
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
            ], [
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
            ]);

            if ($customer) {
                $order  = Order::create([
                    'order_number' =>  $data['order_number'],
                    'sub_total'    =>  $data['subtotal'],
                    'shop_id'  =>  $data['shop_id'],
                    'notes'  =>  $data['note'],
                    'user_id'  =>  auth()->user()->id,
                    'customer_id'  =>  $customer->id,
                    'discount_amount'  =>  $data['discount'],
                    'total_amount'  =>  $data['subtotal'] - $data['discount'],
                    'total_final_amount'  =>  $data['subtotal'] - $data['discount'],
                ]);
                if ($order) {
                    $items = $data['items'];
                    if (count($items) > 0) {
                       foreach ($items as $key => $pro_item) {
                           OrderDetail::create([
                              'order_id' => $order->id,
                              'product_id' => $pro_item['product_id'],
                              'shop_id' =>  $order->shop_id,
                              'quantity_unit_id' =>  $pro_item['quantity_unit_id'],
                              'quantity_unit_value' =>  $pro_item['input_quantity'],
                              'product_name' => $pro_item['product_name'],
                              'product_quantity' => $pro_item['quantity'],
                              'final_quantity' => $pro_item['quantity'],
                              'product_unit_price' => $pro_item['price'],
                              'sub_total' => $pro_item['totalPrice'],
                              'final_amount' => $pro_item['totalPrice'],
                           ]);
                       }
                    }

                    /* create customer transaction */
                    Transaction::create([
                        'customer_id' => $customer->id, 
                        'order_id' => $order->id, 
                        'user_id' => auth()->user()->id, 
                        'status' => 'done', 
                        'type' => 'out', 
                        'flag' => 'order_placed', 
                        'amount' => $data['subtotal'] - $data['discount']
                    ]);

                    /* if customer make any instant payment */
                    if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {
                        Transaction::create([
                            'customer_id' => $customer->id, 
                            'order_id'    => $order->id, 
                            'user_id'     => auth()->user()->id, 
                            'status'      => 'done', 
                            'type'        => 'in', 
                            'flag'        => 'payment', 
                            'amount'      => $data['payment_amount']
                        ]);
                    }
                }
    
            }
            \DB::commit();
            return response()->json(['status'=>true, 'data'=> $order]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    public function getProductsByShop($shop_id) {
        try {
            $data = [];
            $data['products'] =  Product::select('products.*',  \DB::raw('((IFNULL(SWW.shops_stock_quantity_two, 0) + IFNULL(shop_products.quantity, 0) + IFNULL(TA.total_transfer_added, 0)) - (IFNULL(TT.total_transfer, 0) + IFNULL(OD.total_out, 0))) AS shop_quantity'))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer_added, product_id FROM shop_to_shops WHERE shop_to='.$shop_id.' GROUP BY product_id) AS TA'), 'TA.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(ODD.final_quantity) as total_out, ODD.product_id FROM order_details AS ODD LEFT JOIN orders ON ODD.order_id = orders.id WHERE orders.shop_id='.$shop_id.' GROUP BY ODD.product_id) as OD'), 'OD.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity_two, product_id FROM `shop_product_stocks` WHERE shop_id='.$shop_id.'  GROUP BY product_id) as SWW'), function($q) {
                $q->on('SWW.product_id', '=', 'products.id');
            })
            ->where('shop_products.shop_id', $shop_id)
            ->get();
            return response()->json(['status'=>true, 'data'=> $data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
        
    }

    public function show($id) {
        $data['order'] = Order::where('id', $id)->first();
        return view('admin.orders.show', $data);
    }

}
