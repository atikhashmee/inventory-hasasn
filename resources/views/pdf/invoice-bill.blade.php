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
    table.data-table, table.invoice-info, table.summer-table, table.summery-table-left, table.signature-table {
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
    table.sum-total{
        width: 100%;
        margin-left: auto;
        border-collapse: collapse;
    }
    table.sum-total tr:last-child{
        border-top: 3px solid #000;
    }
    table.sum-total tr:last-child td{
        border-top: 3px solid #000;
    }
    table.sum-total tr td:last-child{
        text-align: right;
    }
</style>
<br />
<br />
<br />
<br />
<div class="doc-type">
    <h2>Invoice/Bill</h2>
</div>

<br />

<table class="invoice-info">
    <tr>
        <td width="50%">
            <p><strong>Invoice Number:</strong> {{$order_number}}</p>
            <p><strong>Customer Name:</strong> {{$customer['customer_name']}}</p>
            <p><strong>Phone No:</strong> {{$customer['customer_phone']}}</p>
            <p><strong>E-mail:</strong> {{$customer['customer_email']}}</p>
            <p><strong>Address:</strong>{{$customer['customer_address']}} </p>
        </td>
        <td width="20%">&nbsp;</td>
        <td width="30%">
            <table style="margin-left: auto; text-align: right; width: 100%">
                <tr>
                    <th>Date:</th>
                    <td>{{date('d/m/Y', strtotime($created_at))}}</td>
                </tr>
                <tr>
                    <th>Time:</th>
                    <td>{{date('h:i a', strtotime($created_at))}}</td>
                </tr>
                <tr>
                    <th>Sold By:</th>
                    <td>{{$user['name']}}</td>
                </tr>
            </table>
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
            <th>Unit Price(Taka)</th>
            <th>Total(Taka)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $subtotal = 0;
        @endphp
        @if (count($order_detail) > 0)
            @foreach ($order_detail as $key=> $detail)
            @php
                $subtotal +=  $detail['product_unit_price'] * $detail['product_quantity'];
            @endphp
                <tr>
                    <td>{{++$key}}</td>
                    <td>
                        <span>{{$detail['product_name']}}</span> <br>
                        <span>
                                <span> <b>Origin</b> {{$detail['origin']}}<span> 
                                <span> <b>Brand</b> {{$detail['brand_name']}}<span>
                        </span>
                    </td>
                    <td>
                        {{$detail['quantity_unit_id']!=null? $detail['quantity_unit_value'].' '.$detail['unit']['name']: $detail['product_quantity'].' Unit' }}
                    </td>
                    <td>{{$detail['product_unit_price']}}</td>
                    <td>{{$detail['product_unit_price'] * $detail['product_quantity']}}</td>
                </tr>
            @endforeach
        @endif
        
    </tbody>
</table>
<br />
<br />
@php
    $tnx_amount = $transaction['amount'] ?? 0;
    $today_sales = ($subtotal - $discount_amount);
    $current_due = ($customer['current_due'] - $today_sales) > 0 ? $customer['current_due'] : 0;
    $net_outstanding = ($current_due + $today_sales) -  $tnx_amount;
@endphp
<table class="summer-table">
    <tbody>
        <tr>
            <td width="40%">
                <table class="summery-table-left">
                    <tr>
                        <td>
                            <p>Current Due: {{number_format($current_due, 2, '.', ',')}}</p>
                            <p>Sales: {{number_format(($today_sales) , 2, '.', ',')}}</p>
                            <p>Collected: {{$tnx_amount}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Net Outstanding: {{ number_format($net_outstanding, 2, '.', ',') }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="20%">&nbsp;</td>
            <td width="30%">
                <table class="sum-total">
                    <tr>
                        <td>Subtotal(Taka)</td>
                        <td>{{$subtotal}}</td>
                    </tr>
                    <tr>
                        <td>Discount(Taka)</td>
                        <td>{{$discount_amount}}</td>
                    </tr>
                    <tr>
                        <td><strong>Grand Total</strong>(Taka)</td>
                        <td>{{number_format($subtotal - $discount_amount, 2, '.', ',') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<br />
<br />
<p style="text-align: center; text-transform: uppercase"> <strong>In Word(Taka):</strong> {{numberToWord(($subtotal - $discount_amount))}}</p>
<br />
<br />
<br />
<br />
<br />

<table class="signature-table">
    <tbody>
        <tr>
            <td width="25%">
                    <strong style="border-top: 3px solid #000">Customer Signature</strong>
            </td>
            <td width="40%"></td>
            <td width="25%" style="text-align: right">
                <strong style="border-top: 3px solid #000; margin-left:auto; text-align:right">Authority Signature</strong>
            </td>
        </tr>
    </tbody>
</table>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
@if ($notes)
    <table style="width: 100%">
        <tr>
            <td style="text-align: center;">
                <p style="text-justify: inter-word;">{{$notes}}</p>
            </td>
        </tr>
    </table>
@endif

