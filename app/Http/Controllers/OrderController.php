<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        return view('admin.orders.index');
    }

    public function create() {
        $data['shops'] = Shop::where('status', 'active')->get();
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
                              'product_name' => $pro_item['product_name'],
                              'product_quantity' => $pro_item['quantity'],
                              'final_quantity' => $pro_item['quantity'],
                              'product_unit_price' => $pro_item['price'],
                              'sub_total' => $pro_item['totalPrice'],
                              'final_amount' => $pro_item['totalPrice'],
                           ]);
                       }
                    }
                }
    
            }
            \DB::commit();
            return response()->json(['status'=>true, 'data'=>'Order has been successfully placed']);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
    }

    public function getProductsByShop($shop_id) {
        try {
            $data = [];
            $data['products'] =  Product::select('products.*',  \DB::raw('((IFNULL(SWW.shops_stock_quantity_two, 0) + IFNULL(shop_products.quantity, 0) + IFNULL(TA.total_transfer_added, 0)) - IFNULL(TT.total_transfer, 0)) AS shop_quantity'))
            ->join('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer_added, product_id FROM shop_to_shops WHERE shop_to='.$shop_id.' GROUP BY product_id) AS TA'), 'TA.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity_two, product_id, shop_id FROM `shop_product_stocks` GROUP BY product_id, shop_id) as SWW'), function($q) {
                $q->on('SWW.product_id', '=', 'products.id');
                $q->orOn('shop_products.shop_id', '=', 'SWW.shop_id');
            })
            //->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity, product_id, shop_id FROM `shop_product_stocks` WHERE warehouse_id ='.$warehouse_id.' AND shop_id='.$request->shop_id.' GROUP BY product_id) as SW'), 'SW.product_id', '=', 'products.id')
            ->where('shop_products.shop_id', $shop_id)
            ->get();
            return response()->json(['status'=>true, 'data'=> $data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
        
    }

}
