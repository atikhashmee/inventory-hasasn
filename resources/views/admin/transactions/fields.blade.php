<div class="col-md-6">
    <div class="form-group">
        <label for="customers">Select a Customer <span class="text-danger">*</span></label>
        <select name="customer_id" id="customer_id" class="form-control select2">
            <option value="">Select Customer</option>
            @foreach ($customers as $customer)
                <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="customers">Transaction Type <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control">
            <option value="">Select Transaction Type</option>
            <option value="in">Disposite</option>
            <option value="out">Withdraw</option>
        </select>
    </div>
    <div class="form-group">
        <label for="customers">Flag <span class="text-danger">*</span></label>
        <select name="flag" id="flag" class="form-control">
            <option value="">Select Transaction Flag</option>
            <option value="payment">Payment</option>
            <option value="refund">Refund</option>
        </select>
    </div>
    <div class="form-group">
        <label for="customers">Amount <span class="text-danger">*</span> </label>
        <input type="number" class="form-control" name="amount">
    </div>
    <div class="form-group">
        <label for="customers">Note (Optional)</label>
        <textarea name="note" class="form-control" id="note"></textarea>
    </div>
</div>

