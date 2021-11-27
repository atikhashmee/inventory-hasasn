<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Laracasts\Flash\Flash;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\WarentySerial;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index() {
        $data['orders'] = Order::where(function($q){

            if (request()->query('start')!='' && request()->query('end')!='') {
                $q->whereBetween(\DB::raw('DATE(created_at)'), [request()->query('start'),  request()->query('end')]);
            }

           

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
            if (request()->query('customer_id')!='') {
                $q->where('customer_id', request()->query('customer_id'));
            }
            if (request()->query('order_challan_type')!='') {
                $q->where('order_challan_type', request()->query('order_challan_type'));
            }
        })->orderBy('id', 'DESC')->paginate(100);
        $data['shops'] = Shop::get();
        $data['customers'] = Customer::get();
        return view('admin.orders.index', $data);
    }

    public function create() {
        $shopCollection = Shop::where('status', 'active')->get();
        $shopCollection = $shopCollection->map(function($shop) {
            if (file_exists(public_path().'/uploads/shops/'.$shop->image)  && $shop->image) {
                $shop->image_link = asset('/uploads/shops/'.$shop->image);
            } else {
                $shop->image_link = asset('assets/img/not-found.png');
            }
            return $shop;
        });
        $data['shops'] = $shopCollection;
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
                    'order_challan_type'  =>  strtolower($data['sale_type']),
                    'notes'  =>  $data['note'],
                    'challan_note'  =>  $data['challan_note'],
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
                              'product_original_unit_price' => $pro_item['product_purchase_price'],
                              'product_unit_price' => $pro_item['price'],
                              'sub_total' => $pro_item['totalPrice'],
                              'final_amount' => $pro_item['totalPrice'],
                              'warenty_duration' => $pro_item['warenty_duration'],
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
        $data['wr_order_details'] = OrderDetail::with('warenty')->select('order_details.*')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->where('order_details.order_id', $id)
        ->whereNotNull('products.warenty_duration')
        ->get();
        return view('admin.orders.show', $data);
    }

    public function showReturnLists() {
        $data['orders'] = OrderDetail::select('order_details.*', 'orders.order_number')
        ->where('returned_quantity', '>', 0)
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->paginate(100);
        return view('admin.orders_more.index', $data); 
    }
    public function salesReturnForm() {  
        $data['orders'] = Order::with('shop', 'customer', 'orderDetail', 'orderDetail.product')->get();
        return view('admin.orders_more.sale_return', $data); 
    }

    public function salesReturnUpdate(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => ['required', 'integer', 'exists:orders,id'],
                'detail_id' => ['required', 'integer', 'exists:order_details,id'],
                'quantity'  => ['required'],
                'returnedPrice'  => ['required'],
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status'=> false, 'data'=> null, 'errors' => $validator->errors(), 'error' => ''], 422);
            }
            $od_detail = OrderDetail::where('id', $request->detail_id)->first();
            if ($od_detail) {
                if ($od_detail->final_quantity < $request->quantity) {
                    return response()->json(['status'=> false, 'data'=> null, 'errors' => [], 'error' => 'Returned quantity exceed original quanitty'], 422);
                }

                if ($od_detail->final_amount < $request->returnedPrice) {
                    return response()->json(['status'=> false, 'data'=> null, 'errors' => [], 'error' => 'Returned price exceed original price'], 422);
                }

                $od_detail->returned_quantity = $request->quantity;
                $od_detail->returned_amount = $request->returnedPrice;
                $od_detail->final_quantity = $od_detail->final_quantity - $request->quantity;
                $od_detail->final_amount = $od_detail->final_amount - $request->returnedPrice;
                $od_detail->save();
            }
            return response()->json(['status'=> true, 'msg'=> 'Success', 'data'=> null, 'errors' => [], 'error' => ''], 200);
        } catch (\Exception $e) {
            return response()->json(['status'=> false, 'data'=> null, 'errors' => [], 'error' => $e->getMessage()], 422);
        }
   
    }

    public function salesReturnRollback(Request $request) {
        $validator = Validator::make($request->all(), [
            'detail_id' => ['required', 'integer', 'exists:order_details,id']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $detail = OrderDetail::where('id', $request->detail_id)->first();
        if ($detail) {
            $detail->returned_quantity = 0;
            $detail->final_quantity = $detail->product_quantity;
            $detail->returned_amount = 0;
            $detail->final_amount =  $detail->product_quantity * $detail->product_unit_price;
            $detail->save();
            Flash::success('Successfully rollbacked.');
            return redirect(route('admin.order.return'));
        } else {
            Flash::error('Record not found');
            return redirect(route('admin.order.return'));
        }
    }

    public function setWarentySerial($order_id) {
        $data = [];
        $data['products'] = OrderDetail::with('warenty')->select('order_details.*')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->where('order_details.order_id', $order_id)
        ->whereNotNull('products.warenty_duration')
        ->get();
        $data['order_id'] = $order_id;
        return view('admin.orders.warenty_serial', $data);
    }

    public function submitWarentlySerial(Request $request) {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $order_id = $request->order_id;
        $order_details = $request->serial_number;
        WarentySerial::where('order_id' , $order_id)->delete();

        if (count($order_details) > 0) {
            foreach ($order_details as $detail_id =>  $detail) {
                if (count($detail) > 0) {
                    foreach ($detail as $key => $de) {
                        WarentySerial::create([
                            'order_id' => $order_id,
                            'order_detail_id' => $detail_id,
                            'quanitty_serial_number' => $key,
                            'serial_number' => $de[0],
                        ]);
                    }
                }
            }
        }
        return redirect()->route('admin.orders.show', ['order' => $order_id]);
    }

}
