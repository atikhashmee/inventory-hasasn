
@extends('pdf.app')
@section('content')
    <main>
        <div class="doc-type">
            <h2>Invoice/Bill</h2>
        </div>
        <table class="invoice-info">
            <tr>
                <td width="60%" style="line-height: 5px;">
                    <p><strong>Invoice:</strong> {{$invoice_no}}</p>
                    <p><strong>Customer:</strong> {{$customer['customer_name']}}</p>
                    <p><strong>Phone No:</strong> {{$customer['customer_phone']}}</p>
                    <p><strong>E-mail:</strong> {{$customer['customer_email']}}</p>
                    <p><strong>Address:</strong> {{$customer['customer_address']}} </p>
                </td>
                <td width="20%">&nbsp;</td>
                <td width="20%">
                    <table style="margin-left: auto; text-align: right; width: 100%">
                        <tr>
                            <th style="text-align: left;">Date:</th>
                            <td style="text-align: left;">{{date('d/m/Y', strtotime($created_at))}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Time:</th>
                            <td style="text-align: left;">{{date('h:i a', strtotime($created_at))}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: left;">Sold By:</th>
                            <td style="text-align: left;">{{$user['name']}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br />
        <table class="data-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item</th>
                    <th>Model</th>
                    <th>Brand/Origin</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if (count($order_detail) > 0)
                    @foreach ($order_detail as $key=> $detail)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>
                                <span>{{$detail['product_name']}}</span> <br>
                            </td>
                            <td>
                                <span>{{$detail['model']}}</span> <br>
                            </td>
                            <td>
                                @if (!empty($detail['brand_name']))
                                    <span>{{$detail['brand_name']}}</span>
                                @else 
                                    @if (!empty($detail['origin']))
                                        <span>{{$detail['origin']}}</span>
                                    @endif
                                @endif
                            </td>
                            <td>{{$detail['product_unit_price']}}</td>
                            <td>
                                {{$detail['quantity_unit_id']!=null? $detail['quantity_unit_value'].' '.$detail['unit']['name']: $detail['product_quantity'].' Unit' }}
                            </td>
                            <td>{{ number_format(($detail['product_unit_price'] * $detail['product_quantity']), 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <br />
        <br />
        @php
            $tnx_amount = $total_collected ?? 0;
            $current_due = $customer['current_due'];
            $net_outstanding = $net_outstanding;
        @endphp
        <table class="summer-table">
            <tbody>
                <tr>
                    <td width="40%">
                        <table class="summery-table-left">
                            <tr>
                                <td style="line-height: 8px;">
                                    <span style="display: block; padding: 5px 0px">Current Due: {{number_format($current_due, 2, '.', ',')}}</span>
                                    <span style="display: block; padding: 5px 0px">Sales: {{number_format(($today_sales) , 2, '.', ',')}}</span>
                                    <span style="display: block; padding: 5px 0px">Collected: {{number_format(($tnx_amount) , 2, '.', ',')}}</span>
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
                                <td>{{number_format($sub_total, 2, '.', ',')}}</td>
                            </tr>
                            <tr>
                                <td>Discount(Taka)</td>
                                <td>{{number_format($discount_amount, 2, '.', ',')}}</td>
                            </tr>
                            <tr>
                                <td><strong>Grand Total</strong>(Taka)</td>
                                <td>{{number_format($sub_total - $discount_amount, 2, '.', ',') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        <br />
        <p style="text-align: center; text-transform: uppercase"> <strong>In Word(Taka):</strong> {{numberToWord(($sub_total - $discount_amount))}}</p>
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
        @if ($notes)
            <table style="width: 100%">
                <tr>
                    <td style="text-align: center;">
                        <p style="text-justify: inter-word;">{{$notes}}</p>
                    </td>
                </tr>
            </table>
        @endif 
    </main>
@endsection