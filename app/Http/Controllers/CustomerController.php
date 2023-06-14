<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Extra\Util;
use App\Models\Customer;
use Laracasts\Flash\Flash;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use Util;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index(Request $request)
    {
        $user = auth()->user();
        try {
            $customer_sql = Customer::select('customers.*', \DB::raw('IFNULL(B.total_orders, 0) as totalOrdersCount'), 'B.order_ids')
                ->leftJoin(\DB::raw('(SELECT COUNT(id) as total_orders, GROUP_CONCAT(id) as order_ids, customer_id FROM orders GROUP BY customer_id) AS B'), 'customers.id', '=', 'B.customer_id');
                if ($user->role != 'admin') {
                    $customer_sql->where('shop_id', $user->shop_id);
                }
                $customer_sql->where(function($q) use($request) {
                    if ($request->customer_type) {
                        $q->where('customer_type', $request->customer_type);
                    }

                    if ($request->phone_number) {
                        $q->where('customer_phone', 'LIKE', '%'.$request->phone_number.'%');
                    }

                    if ($request->search) {
                        $q->where('customer_name', 'LIKE', '%'.$request->search.'%');
                        $q->orWhere('customer_email', 'LIKE', '%'.$request->search.'%');
                        $q->orWhere('district', 'LIKE', '%'.$request->search.'%');
                    }
                });
                $customer_sql->orderBy('totalOrdersCount', 'DESC');
                $data['customers'] =  $customer_sql->paginate(100);
                $data['customer_types'] = $this->customer_types;
            return view('admin.customers.index', $data);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function getAllCustomerJson(Request $request) {
        try {
            $data['customers'] = Customer::where('customer_name', 'LIKE', '%'.$request->term.'%')->get();
            return response()->json(['status'=> true, 'data'=>$data]);
        } catch (\Exception $e) {
            return response()->json(['status'=> false, 'data'=>$e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create', ['customer_types' => $this->customer_types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'customer_name' => 'required|max:200',
            'customer_email' => 'nullable|max:200|email|unique:customers,customer_email',
            'customer_type' => 'required|in:'.implode(',', $this->customer_types),
            'customer_phone' => 'required|unique:customers,customer_phone',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = auth()->user();
        if ($user->role != 'admin') {
            $data['shop_id'] = $user->shop_id;
        } else {
            $data['shop_id'] = 0;
        }
        $data['user_id'] = $user->id;
        $customer = Customer::create($data);
        
        if ($customer) {
            Flash::success('Saved successfully.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $data['customer'] = $customer;
        $data['customer_types'] = $this->customer_types;
        return view('admin.customers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->except('_token', '_method');
        $udpated = $customer->update($data);
        if ($udpated) {
            Flash::success('Updated successfully.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        Flash::success('Deleted successfully.');
        return redirect()->route('admin.customers.index');
    }

    public function adjustBalance($customerId) {
        try {
            \DB::beginTransaction();
            $customers = Customer::whereIn('id', [$customerId])->get();
            if (!empty($customers)) {
                foreach ($customers as $customer) {
                    $transactions = Transaction::where('customer_id', $customer->id)->get();
                    if (!empty($transactions)) {
                        Transaction::where("customer_id", $customer->id)->delete();
                    }
                    $orders = Order::with("orderDetail")->where('customer_id', $customer->id)->get();
                    if (!empty($orders)) {
                        foreach ($orders as $order) {
                            $totalOrderAmount = 0;
                            $totalItemPrice = [];
                            if (count($order->orderDetail) > 0) {
                                foreach ($order->orderDetail as $orderDetail) {
                                    $productUnitPrice = $orderDetail->product_unit_price;
                                    $totalFinalQuantity = $orderDetail->final_quantity;
                                    $totalItemPrice[] = ($totalFinalQuantity * $productUnitPrice);  
                                }
                            }
                            if (count($totalItemPrice) > 0) {
                                $totalOrderAmount = array_sum($totalItemPrice);
                            }
    
                            if ($totalOrderAmount > 0) {
                                Transaction::updateOrCreate([
                                    'order_id' => $order->id,
                                    'flag' => 'order_placed', 
                                ], [
                                    'customer_id' => $customer->id, 
                                    'order_id' => $order->id, 
                                    'user_id' => $order->user_id, 
                                    'status' => 'done', 
                                    'type' => 'out', 
                                    'flag' => 'order_placed', 
                                    'amount' => $totalOrderAmount
                                ]);
    
                                Transaction::updateOrCreate([
                                    'order_id' => $order->id,
                                    'flag'     => 'payment', 
                                ], [
                                    'customer_id' => $customer->id, 
                                    'order_id'    => $order->id, 
                                    'user_id'     => $order->user_id, 
                                    'status'      => 'done', 
                                    'type'        => 'in', 
                                    'flag'        => 'payment', 
                                    'payment_type'=> "Cash", 
                                    'amount'      => $totalOrderAmount
                                ]);
                            }
                        }
                    }        
                }
            }
            if (!$customer) {
               throw new \Exception("Customer not found", 1);
            }
            \DB::commit();
            return "done!";
        } catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage(); 
        }
    }
}
