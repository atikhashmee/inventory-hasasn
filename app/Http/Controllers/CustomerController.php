<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data['customers'] = Customer::select('customers.*', \DB::raw('IFNULL(B.total_orders, 0) as totalOrdersCount'), 'B.order_ids')
            ->leftJoin(\DB::raw('(SELECT COUNT(id) as total_orders, GROUP_CONCAT(id) as order_ids, customer_id FROM orders GROUP BY customer_id) AS B'), 'customers.id', '=', 'B.customer_id')
            ->orderBy('totalOrdersCount', 'DESC')
            ->paginate(100);
            return view('admin.customers.index', $data);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function getAllCustomerJson(Request $request) {
        try {
            $data['customers'] = Customer::get();
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
        return view('admin.customers.create');
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
            'customer_email' => 'required|max:200|email|unique:customers,customer_email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $customer = Customer::create([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'customer_address' => $data['customer_address'],
        ]);
        
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
}
