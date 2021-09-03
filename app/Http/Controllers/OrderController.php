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
            $customer = Customer::create([
                'customer_name' => $data['customer_name'],
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
        return Product::get();
    }

}
