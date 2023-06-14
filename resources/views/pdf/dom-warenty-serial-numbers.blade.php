@extends('pdf.app')
@section('content')
<div class="doc-type">
    <h2>Warranty Card</h2>
</div>
<br />
<table class="invoice-info">
    <tr>
        <td width="55%">
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
                    <th>Warranty Period (End date)</th>
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
<p style="text-align: center; text-transform: uppercase;  text-align: justify"> 
    1. Warranty Void : Sticker removed items, Burn case, Water case & physically damage products <br>
    2. Warranty period: One year service warranty <br>
    3. Warranty coverage: Warranty does not include the price of spare parts<br>
</p>  
@endsection