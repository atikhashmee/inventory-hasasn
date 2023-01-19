<div class="col-md-6">
    <div class="form-group">
        <label for="customers">Select a Customer <span class="text-danger">*</span></label>
        <select name="customer_id" id="customer_id" class="form-control select2" onchange="changeCustomer(this)">
            <option value="">Select Customer</option>
            @foreach ($customers as $customer)
                <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
            @endforeach
        </select>
    </div>
    {{-- <div class="form-group">
        <label for="customers">Transaction Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control">
            <option value="">Select Transaction Type</option>
            <option value="in">Deposit</option>
            <option value="out">Withdraw</option>
        </select>
    </div> --}}
    <div class="form-group">
        <label for="customers">Flag <span class="text-danger">*</span></label>
        <select name="flag" id="flag" class="form-control">
            <option value="">Select Transaction Flag</option>
            <option value="payment">Payment</option>
            <option value="refund">Refund</option>
        </select>
    </div>
    <div class="form-group">
        <label for="customers">Select Invoice <span class="text-danger">*</span></label>
        <select name="order_id" id="order_id" class="form-control">
        </select>
    </div>
    <div class="form-group">
        <label for="">Total Payable</label>
        <p>
            (+) = Shop Owner needs to pay to customer </br>
            (-) = customers needs to pay to Shop Owner
        </p>
        <input type="number" readonly class="form-control" id="payable">
    </div>
    <div class="form-group">
        <label for="customers">Amount <small>(Put only positive number)</small> <span class="text-danger">*</span> </label>
        <input type="number" class="form-control" name="amount">
    </div>
    <div class="form-group">
        <label for="">Payment Type </label>
        <select name="payment_type" id="payment_type" class="form-control">
            <option value="">Select a Payment Type</option>
            @foreach (App\Models\Transaction::$paymentType as $paymentType)
                <option>{{$paymentType}}</option>   
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="customers">Note (Optional)</label>
        <textarea name="note" class="form-control" id="note"></textarea>
    </div>
</div>

