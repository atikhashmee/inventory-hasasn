<table class="table table-bordered">
    <tr>
        <td width="30%">
            <p><strong>Customer Name:</strong> {{$challan->customer->customer_name}}</p>
            <p><strong>Phone No:</strong> {{$challan->customer->customer_phone}}</p>
            <p><strong>E-mail:</strong> {{$challan->customer->customer_email}}</p>
            <p><strong>Address:</strong>{{$challan->customer->customer_address}} </p>
        </td>
        <td width="50%">&nbsp;</td>
        <td width="30%">
            <table style="margin-left: auto; text-align: right; width: 100%">
                <tr>
                    <th>Date:</th>
                    <td>{{date('d/m/Y', strtotime($challan->created_at))}}</td>
                </tr>
                <tr>
                    <th>Time:</th>
                    <td>{{date('h:i a', strtotime($challan->created_at))}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="data-table table table-bordered">
    <thead>
        <tr>
            <th>SL</th>
            <th>Product Type</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>{{$challan->product_type}}</td>
            <td>{{$challan->quantity}} {{$challan->unit->name}}</td>
        </tr>
    </tbody>
</table>
<table class="ml-auto table table-bordered" style="width: 20%">
    <tbody>
        <tr>
            <td><strong>Total Payable</strong></td>
            <td>{{$challan->total_payable}}</td>
        </tr>
    </tbody>
</table>
