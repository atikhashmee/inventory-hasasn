<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Unit;
use App\Models\Order;
use App\Models\Product;
use App\Http\Extra\Util;
use App\Models\Customer;
use App\Models\Supplier;
use Laracasts\Flash\Flash;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ShopInventory;
use App\Models\WarentySerial;
use Illuminate\Validation\Rule;
use App\Models\ShopProductStock;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\throwException;
use Illuminate\Database\Events\TransactionBeginning;

class OrderController extends Controller
{
    use Util;
    public function index(Request $request) {
        $user = auth()->user();
        $data['orders'] = $this->orderLists($request, $user);
        $data['serial'] = pagiSerial($data['orders'], 100);
        if ($user->role == 'admin') {
            $data['shops']  = Shop::get();
            $data['customers'] = Customer::get();
        } else {
            $data['customers'] = Customer::where('user_id', $user->id)->get();
        }
        $data['user'] = $user;
        $data['suppliers'] = Supplier::get();
        return view('admin.orders.index', $data);
    }

    public function orderLists(Request $request, $user) {
        $order_sql =  Order::select('orders.*',
         \DB::raw("IFNULL(OD.total_warenty_items, 0) as wr_order_details"),
         \DB::raw("IFNULL(A.order_total_payemnt, 0) as order_total_payemnt"),
    //'transactions.payemnt_type'
           )
        ->leftjoin(\DB::raw("(SELECT COUNT(order_details.id) AS total_warenty_items, order_details.order_id FROM order_details INNER JOIN products ON products.id = order_details.product_id WHERE products.warenty_duration IS NOT NULL GROUP BY order_details.order_id) AS OD"), 'OD.order_id', '=', 'orders.id')
        ->leftjoin(\DB::raw("(SELECT SUM(amount) AS order_total_payemnt, order_id FROM transactions WHERE flag='payment' GROUP BY order_id) AS A"), 'A.order_id', '=', 'orders.id')
        // ->leftJoin('transactions', function($q) {
        //     $q->on('transactions.order_id', '=', 'orders.id');
        //     $q->having('transactions.payment_type', 'payment');
        // })
        ->where(function($q) {
            if (request()->query('start')!='' && request()->query('end')!='') {
                $q->wherebetween(\DB::raw('date(created_at)'), [request()->query('start'),  request()->query('end')]);
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
            if (!is_null(request()->query('order_status')) && request()->query('order_status') != 'All') {
                if (request()->query('order_status') == "Drafted") {
                    $q->where('status', 'Drafted');
                } else {
                    $q->where('status', '!=', 'Drafted');
                }
            }
        });

        if (request()->query('supplier_id')!='') {
            $order_sql->join(\DB::raw('(SELECT order_id, shop_product_stocks.supplier_id FROM order_details
             INNER JOIN shop_inventories ON shop_inventories.order_detail_id = order_details.id
             INNER JOIN shop_product_stocks ON shop_product_stocks.id = shop_inventories.stock_id
             WHERE shop_product_stocks.supplier_id = "'.request()->query('supplier_id').'"
             ) AS SO'), 'SO.order_id', '=', 'orders.id');
        }
        if ($user->role != 'admin') {
            $order_sql->where('user_id', $user->id);
        }
        
        return $order_sql->orderBy('id', 'DESC')
        ->paginate(100);
    }

    public function create(Request $request) {
        $user =  auth()->user();
        $data['user'] = $user;
        $data['role'] = $user->role;
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
        $data['user'] = $user;
        $data['role'] = $user->role;
        $data['customer_types'] = $this->customer_types;
        if (isset($request->order_id) && !empty($request->order_id)) {
            $order = Order::with('orderDetail', 'customer')->where('id', $request->order_id)->where("status", "Drafted")->first();
            if (!$order) {
                Flash::error('Order Not');
                $data["order"] = null;
            } else {
                Flash::success('Order Found.');
                $data["order"] = $order;
            }
        }
        return view('admin.orders.create', $data);
    }

    public function userOrderCreate() {
        $data = [];
        $user =  auth()->user();
        $data['units'] = Unit::select('id', 'name', 'quantity_base')->where('status', 'active')->get();
        $data['user'] = $user;
        $data['role'] = $user->role;
        return view('user.new_order', $data);
    }

    public function userOrderLists(Request $request) {
        $user = auth()->user();
        $data['orders'] = $this->orderLists($request, $user);
        $data['serial'] = pagiSerial($data['orders'], 100);
        $data['shops']  = Shop::get();
        $data['customers'] = Customer::get();
        return view('user.orders', $data);
    }

    public function userOrderDetail($order_id) {
        $data['order'] = Order::select(
            'orders.*', 
            \DB::raw("(SELECT id FROM orders WHERE id > ".$order_id." LIMIT 1) as next_order_id"),
            \DB::raw("(SELECT id FROM orders WHERE id < ".$order_id." ORDER BY id DESC LIMIT 1) as prev_order_id")
            )
        ->where('id', $order_id)->first();
        $data['wr_order_details'] = OrderDetail::with('warenty')->select('order_details.*')
        ->join('products', 'products.id', '=', 'order_details.product_id')
        ->where('order_details.order_id', $order_id)
        ->whereNotNull('products.warenty_duration')
        ->get();
        return view('user.order_detail', $data);
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
            $user = auth()->user(); 
            $shop_id = $user->shop_id;
            if ($user->role == 'admin') {
                $shop_id = $data['shop_id'];
            }

            $data['shop_id'] = $shop_id;

            $validator = Validator::make($data, [
                "customer_name" => "required",
                "customer_phone" => "required",
                "shop_id" => ["required", "exists:shops,id"],
                "order_status" => ["required", "boolean"],
            ]); 

            if ($validator->fails()) {
                throw new \Exception(json_encode($validator->getMessageBag()->all()), 1);
            }

            /**
             * manual validation code 
             */
            if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {
                if ($data['payment_type'] == null) {
                    throw new \Exception("Please select a payment type", 1);
                }
            }
            if (!isset($data['customer_phone']) || $data['customer_phone'] == null) {
                throw new \Exception("Customer Phone is required", 1);
            }
            
            if (!isset($data['customer_name']) || $data['customer_name'] == null) {
                throw new \Exception("Customer Name is required", 1);
            }
            
            $customer = Customer::updateOrCreate([
                'customer_phone' => $data['customer_phone'],
            ], [
                'shop_id' => $shop_id,
                'user_id' => $user->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'customer_type' => $data['customer_type'],
                'district' => $data['district'],
            ]);

            if ($customer) {
                $order  = Order::updateOrCreate([
                    'order_number' =>  $data['order_number'],
                ], [
                    'order_number' =>  $data['order_number'],
                    'sub_total'    =>  $data['subtotal'],
                    'shop_id'  =>  $data['shop_id'],
                    'order_challan_type'  =>  strtolower($data['sale_type']),
                    'notes'  =>  $data['note'],
                    'status'  =>  $data['order_status'] ==  true ? "Drafted" : "Pending" ,
                    'challan_note'  =>  $data['challan_note'],
                    'user_id'  =>  $user->id,
                    'customer_id'  =>  $customer->id,
                    'discount_amount'  =>  $data['discount'],
                    'total_amount'  =>  $data['subtotal'] - $data['discount'],
                    'total_final_amount'  =>  $data['subtotal'] - $data['discount'],
                ]);
                if ($order) {
                    $items = $data['items'];
                    if (count($items) > 0) {
                        foreach ($items as $key => $pro_item) {
                            if ($pro_item['quantity'] == 0) {
                                throw new \Exception($pro_item['product_name']." Quantity has to be at least 1", 1);
                            }

                            $order_detail =  OrderDetail::updateOrCreate([
                                'order_id' => $order->id,
                                'product_id' => $pro_item['product_id'],
                            ], [
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
                        //update product selling price with the latest one
                        Product::where("id", $pro_item['product_id'])->update(["selling_price" => $pro_item['price']]);
                        $settle_quantity = $order_detail->final_quantity;
                        if ($order_detail) {
                            $stocks = ShopProductStock::select('shop_product_stocks.*', 'ST.total_stock_out', DB::raw('(shop_product_stocks.quantity - IFNULL(ST.total_stock_out, 0)) AS stockQty'))
                            ->leftJoin(DB::raw("(SELECT SUM(quantity) as total_stock_out, stock_id FROM shop_inventories GROUP BY stock_id) as ST"), 'shop_product_stocks.id', '=', 'ST.stock_id')
                            ->where('shop_id', $order_detail->shop_id)
                            ->where('product_id', $order_detail->product_id)
                            ->having('stockQty', '>', 0)
                            ->get();

                            foreach ($stocks as $key => $st) {
                                $checkQty = ($settle_quantity - $st->stockQty);
                                if ($checkQty > 0) {
                                    $stQty = $st->stockQty;
                                    } else {
                                        $stQty = $settle_quantity;
                                    }
                                    $stOut =  ShopInventory::create([
                                        'type' => 'order_placed',
                                        'order_detail_id' => $order_detail->id,
                                        'stock_id' => $st->id,
                                        'product_id' => $st->product_id,
                                        'shop_id' =>  $st->shop_id,
                                        'quantity' =>  $stQty
                                    ]);

                                    if ($stOut) {
                                        $settle_quantity = $settle_quantity-$stOut->quantity;
                                    }

                                    if ($settle_quantity == 0) {
                                        break;
                                    }
                            }
                        }

                            if ($settle_quantity != 0) {
                                throw new \Exception($pro_item['product_name']." Given Quantity exceeds stock", 1);
                            }
                        }
                    } else {
                        throw new \Exception("No Item selected", 1);
                    }

                    /* create customer transaction */
                    Transaction::updateOrCreate([
                        'order_id' => $order->id,
                        'flag' => 'order_placed', 
                    ], [
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
                        Transaction::updateOrCreate([
                            'order_id' => $order->id,
                            'flag'     => 'payment', 
                        ], [
                            'customer_id' => $customer->id, 
                            'order_id'    => $order->id, 
                            'user_id'     => auth()->user()->id, 
                            'status'      => 'done', 
                            'type'        => 'in', 
                            'flag'        => 'payment', 
                            'payment_type'=> $data['payment_type'], 
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
            $user = auth()->user();
            $product_sql =  Product::with('brand')->select('products.*',  \DB::raw('(IFNULL(spQ.shop_stock_in, 0) - IFNULL(spO.shop_stock_out, 0)) AS shop_quantity'))
            ->leftJoin('shop_products', 'shop_products.product_id', '=', 'products.id')
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_in, product_id, shop_id FROM shop_product_stocks GROUP BY shop_id, product_id) as spQ'), function($q) use($shop_id) {
                $q->on('spQ.product_id', '=', 'products.id');
                $q->where('spQ.shop_id', $shop_id);
            })
            ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shop_stock_out, product_id, shop_id FROM shop_inventories GROUP BY shop_id, product_id) as spO'), function($q) use($shop_id) {
                $q->on('spO.product_id', '=', 'products.id');
                $q->where('spO.shop_id', $shop_id);
            });
            // ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer, product_id FROM shop_to_shops WHERE shop_from='.$shop_id.' GROUP BY product_id) AS TT'), 'TT.product_id', '=', 'products.id')
            // ->leftJoin(\DB::raw('(SELECT SUM(quantity) as total_transfer_added, product_id FROM shop_to_shops WHERE shop_to='.$shop_id.' GROUP BY product_id) AS TA'), 'TA.product_id', '=', 'products.id')
            // ->leftJoin(\DB::raw('(SELECT SUM(ODD.final_quantity) as total_out, ODD.product_id FROM order_details AS ODD LEFT JOIN orders ON ODD.order_id = orders.id WHERE orders.shop_id='.$shop_id.' GROUP BY ODD.product_id) as OD'), 'OD.product_id', '=', 'products.id')
            // ->leftJoin(\DB::raw('(SELECT SUM(quantity) as shops_stock_quantity_two, product_id FROM `shop_product_stocks` WHERE shop_id='.$shop_id.'  GROUP BY product_id) as SWW'), function($q) {
            //     $q->on('SWW.product_id', '=', 'products.id');
            // })
            if ($user->role != 'admin') {
                $product_sql->where(function($q) use($user) {
                    $q->where('products.user_id', $user->id);
                    $q->orWhere('shop_products.shop_id', $user->shop_id);
                });
            } else {
                $product_sql->where(function($q) use($shop_id) {
                    $q->where('shop_products.shop_id', $shop_id);
                });
            }
            $data['products'] = $product_sql->get();
            return response()->json(['status'=>true, 'data'=> $data]);
        } catch (\Exception $e) {
            return response()->json(['status'=>false, 'data'=>$e->getMessage()]);
        }
        
    }

    public function show($id) {
        $data['order'] = Order::select(
            'orders.*', 
            \DB::raw("(SELECT id FROM orders WHERE id > ".$id." LIMIT 1) as next_order_id"),
            \DB::raw("(SELECT id FROM orders WHERE id < ".$id." ORDER BY id DESC LIMIT 1) as prev_order_id")
            )
        ->where('id', $id)->first();
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
        ->orderBy('order_details.id', 'DESC')
        ->paginate(100);
        return view('admin.orders_more.index', $data); 
    }
    public function salesReturnForm() {  
        $data['orders'] = Order::with(['shop', 'customer', 'orderDetail' => function($q) {
            $q->select('order_details.*', \DB::raw("0 as input_quantity"), \DB::raw("0 as input_price"), \DB::raw("false as is_return"));
        }, 'orderDetail.product'])->get();
        return view('admin.orders_more.sale_return', $data); 
    }

    public function salesReturnUpdate(Request $request) {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'order_id' => ['required', 'integer', 'exists:orders,id'],
                'product_lists' => ['required', 'array'],
                'product_lists.*.input_quantity' => ['required', 'integer'], //, 'lte:final_quantity'
            ]);
    
            //'returnedPrice'  => ['required'],
            if ($validator->fails()) {
                return response()->json(['status'=> false, 'data'=> null, 'errors' => $validator->errors(), 'error' => ''], 422);
            }
            $data = $request->all();

            $totalQuantity = 0;
            $returnedQuantity = 0;
            $totalPrice = 0;
            if (!empty($data["product_lists"])) {
                foreach ($data["product_lists"] as $product) {
                    $totalQuantity += intval($product["final_amount"]);
                    $returnedQuantity += intval($product["input_quantity"]);
                    $totalPrice += intval($product["input_quantity"]) * floatval($product["product_unit_price"]);
                }
            }

            if ($returnedQuantity > $totalQuantity) {
                return response()->json(['status'=> false, 'data'=> null, 'errors' => [], 'error' => 'Returned quantity exceeds original quanitty'], 422);
            }

            //if all validation passes
            if (!empty($data["product_lists"])) {
                foreach ($data["product_lists"] as $orderDetail) {
                    if (intval($orderDetail["input_quantity"]) > 0) {
                        $od_detail = OrderDetail::select('order_details.*', 'orders.customer_id')
                        ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
                        ->where('order_details.id', $orderDetail["id"])->first();
                        if ($od_detail) {
                            $returnPrice = intval($orderDetail["input_price"]) < 1 ? ($orderDetail["input_quantity"] * $product["product_unit_price"]) : $orderDetail["input_price"];
                            $shopStock = ShopProductStock::create([
                                'user_id' => $user->id,
                                'order_detail_id' => $od_detail->id,
                                'shop_id' => $od_detail->shop_id,
                                'product_id' => $od_detail->product_id,
                                'quantity' => $orderDetail["input_quantity"],
                                'type' => 'sale_return',
                                'price' => $returnPrice
                            ]);
        
                            if ($shopStock) {
                                $customerIn = Transaction::create([
                                    'customer_id' => $od_detail->customer_id, 
                                    'order_id'    => $od_detail->order_id, 
                                    'order_detail_id' => $od_detail->id,
                                    'user_id'     => $user->id, 
                                    'status'      => 'done', 
                                    'type'        => 'in', 
                                    'flag'        => 'sell_return', 
                                    'amount'      => $returnPrice
                                ]);
            
                                if ($customerIn && $request->cash_returned) {
                                    $customerout = Transaction::create([
                                        'customer_id' => $od_detail->customer_id, 
                                        'order_id'    => $od_detail->order_id, 
                                        'order_detail_id' => $od_detail->id,
                                        'user_id'     => $user->id, 
                                        'status'      => 'done', 
                                        'type'        => 'out', 
                                        'flag'        => 'refund', 
                                        'amount'      => $returnPrice
                                    ]);
                                }
                            }
    
                            $totalQty = ShopProductStock::where('order_detail_id', $od_detail->id)
                            ->where('product_id', $od_detail->product_id)
                            ->where('type', 'sale_return')
                            ->sum('quantity');
            
                            //total returned price
                            $totalreturnedPrice = Transaction::where('order_detail_id', $od_detail->id)
                            ->where('flag', 'sell_return')
                            ->sum('amount');
    
                            $od_detail->returned_quantity = $totalQty;
                            $od_detail->returned_amount = $totalreturnedPrice;
                            $od_detail->final_quantity = intval($od_detail->product_quantity) - intval($totalQty);
                            $od_detail->final_amount = $od_detail->sub_total - $totalreturnedPrice;
                            $od_detail->save();
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['status'=> true, 'msg'=> 'Success', 'data'=> null, 'errors' => [], 'error' => ''], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
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
            $savedDetailed = $detail->save();
            if ($savedDetailed) {
                Transaction::where('order_detail_id', $detail->id)
                ->where('flag', 'sell_return')
                ->delete();

                ShopProductStock::where('order_detail_id', $detail->id)
                ->where('product_id', $detail->product_id)
                ->where('type', 'sale_return')
                ->delete();
            }
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
