<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Laracasts\Flash\Flash;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $transSql  = Transaction::select('transactions.*', 'orders.order_number');
        $transSql->leftJoin('orders', 'orders.id', '=', 'transactions.order_id');
        $transSql->where(function($q) {
            if (request()->query('start')!='' && request()->query('end')!='') {
                $q->whereBetween('transactions.created_at', [date(request()->query('start'). ' 00:00:00'),  date(request()->query('end'). ' 23:59:59')]);

            }

            if (request()->query('customer_id')!='') {
                $q->where('transactions.customer_id', request()->query('customer_id'));
            }
            
            if (request()->query('payment_type')!='') {
                $q->where('transactions.payment_type', request()->query('payment_type'));
            }
        });
         
        // if ($user->role != 'admin') {
        //     $transSql->where('transactions.user_id', $user->id);
        // }

        $data['transactions'] = $transSql->orderBy('transactions.id', 'DESC')->paginate(100);
        $data['totalDiposit'] = $data['transactions']->getCollection()->reduce(function($total, $item){
            if ($item->type == 'in') {
                return $total + $item->amount;
            }
            return $total;
        }, 0);
        $data['totalWithdraw'] = $data['transactions']->getCollection()->reduce(function($total, $item){
            if ($item->type=='out') {
                return $total + $item->amount;
            }
            return $total;
        }, 0);

        // if ($user->role == 'admin') {
        //     $data['customers'] = Customer::get();
        // } else {
        //     $data['customers'] = Customer::where('shop_id', $user->shop_id)->get();
        // }
        $data['customers'] = Customer::get();

        return view('admin.transactions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $customer_sql = Customer::with(["orders" => function($q) {
            $q->select("orders.*", "A.order_total_payemnt");
            $q->leftjoin(\DB::raw("(SELECT SUM(amount) AS order_total_payemnt, order_id FROM transactions WHERE flag='payment' GROUP BY order_id) AS A"), 'A.order_id', '=', 'orders.id');
            $q->orderBy("orders.id", 'DESC');
        }])->select('customers.id', 'customers.customer_name', \DB::raw('IFNULL(TD.total_deposit, 0) as total_deposit'), \DB::raw('IFNULL(TW.total_withdraw, 0) as total_withdraw'))
        ->leftJoin(\DB::raw('(SELECT SUM(amount) as total_deposit, customer_id FROM transactions WHERE type="in" GROUP BY customer_id) AS TD'), 'TD.customer_id', '=', 'customers.id')
        ->leftJoin(\DB::raw('(SELECT SUM(amount) as total_withdraw, customer_id FROM transactions WHERE type="out" GROUP BY customer_id) AS TW'), 'TW.customer_id', '=', 'customers.id');
        // if ($user->role != 'admin') {
        //     $customer_sql->where("shop_id", $user->shop_id);
        // } 
        $data['customers'] = $customer_sql->orderBy("id", "DESC")->get();

        
        return view('admin.transactions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|integer|exists:customers,id',
                'type' => 'required|in:in,out',
                'flag' => 'required|in:payment,refund',
                'amount' => 'required',
                'order_id' => 'sometimes|nullable|exists:orders,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $data = $request->except('_token');
            $data['user_id'] = $user->id;
            $tnx = Transaction::create($data);
            if ($tnx) {
                Flash::success('Transaction created');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        
        Flash::success('Transaction deleted');
        return redirect()->back();
    }
}
