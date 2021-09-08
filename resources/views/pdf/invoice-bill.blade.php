<style>
    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    .doc-type{
        background: #6d5e5e;
        margin: 0 auto;
        text-align: center;
        padding: 5px 0;
        width: 200px;
    }
    .doc-type h2{
        color: #fff;
    }
    table.data-table, table.invoice-info, table.summer-table, table.summery-table-left {
        width: 100%;
    }
    table.data-table tr td, table.data-table tr th, table.summery-table-left tr td{
        border: 1px solid #463838;
        border-spacing: none;
        padding: 3px;
    }
    table.data-table, table.summery-table-left{
        border-collapse: collapse;
    }
    table.data-table tr td{
        text-align: center;
    }
</style>

<div class="doc-type">
    <h2>Invoice/BIll</h2>
</div>

<br />
<table class="invoice-info">
    <tr>
        <td width="30%">
            <p><strong>Invoice Number:</strong> {{$order_number}}</p>
            <p><strong>Customer Name:</strong> {{$customer['customer_name']}}</p>
            <p><strong>Phone No:</strong> {{$customer['customer_phone']}}</p>
            <p><strong>E-mail:</strong> {{$customer['customer_email']}}</p>
            <p><strong>Address:</strong>{{$customer['customer_address']}} </p>
        </td>
        <td width="50%">&nbsp;</td>
        <td width="30%">
            <div style="margin-left: auto">
                    <p><strong>Date:</strong> {{date('d/m/Y', strtotime($created_at))}}</p>
                    <p><strong>Time:</strong> {{date('h:i a', strtotime($created_at))}}</p>
                    <p><strong>Sold By:</strong> {{$user['name']}}</p>
            </div>
        </td>
    </tr>
</table>
<br />
<br />

<table class="data-table">
    <thead>
        <tr>
            <th>SL</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @if (count($order_detail) > 0)
            @foreach ($order_detail as $detail)
                <tr>
                    <td>{{$detail['id']}}</td>
                    <td>{{$detail['product_name']}}</td>
                    <td>{{$detail['product_quantity']}}</td>
                    <td>&pound;{{$detail['product_unit_price']}}</td>
                    <td>&pound;{{$detail['product_unit_price'] * $detail['product_quantity']}}</td>
                </tr>
            @endforeach
        @endif
        
    </tbody>
</table>
<br />
<br />
<table class="summer-table">
    <tbody>
        <tr>
            <td width="30%">
                <table class="summery-table-left">
                    <tr>
                        <td>
                            <p>Previous Due: 0BDT</p>
                            <p>Sales: 1000BDT</p>
                            <p>Collected: 0BDT</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Net Outstanding: 11000BDT</strong>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30%">&nbsp;</td>
            <td width="30%">
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td>11000BDT</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

