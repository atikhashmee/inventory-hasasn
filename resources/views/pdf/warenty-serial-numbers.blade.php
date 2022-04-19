<style>
    *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-size: 18px;
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
    <h2>Warenty Card</h2>
</div>
<br />
<table class="invoice-info">
    <tr>
        <td width="50%">
            <p><strong>Invoice Number:</strong> {{$order_number}}</p>
            <p><strong>Customer Name:</strong> {{$customer['customer_name']}}</p>
            <p><strong>Phone No:</strong> {{$customer['customer_phone']}}</p>
            <p><strong>E-mail:</strong> {{$customer['customer_email']}}</p>
            <p><strong>Address:</strong> {{$customer['customer_address']}} </p>
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
@if (count($serials) > 0)
    @foreach ($serials as $sl =>  $product)
        <table class="data-table">
            <thead>
                <tr>
                    {{-- <th>{{++$sl}}</th>
                    <th>{{ $product['product_name']}}</th> --}}
                    <th>SL #{{++$sl}}</th>
                    <th>Item Name</th>
                    <th>Serial Number</th>
                    <th>Warenty Period (End date)</th>
                </tr>
            </thead>
            <tbody>
                @if (count($product['serial_items']) > 0)
                    @foreach ($product['serial_items'] as $serial => $number)
                        <tr>
                            <td>{{$serial}}</td>
                            <td>{{ $product['product_name']}}</td>
                            <td>{{$number['s_number']}}</td>
                            <td>{{$number['warenty_preiod']}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    @endforeach
@endif
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
<p style="text-align: center; text-transform: uppercase;  text-align: justify"> 1. Warenty Void : Sticker removed items, Burn case, Water case & physically damage products <br>
    2. Warranty period: One year service warranty <br>
    3. Warranty coverage: Warranty does not include the price of spare parts<br>
</p>