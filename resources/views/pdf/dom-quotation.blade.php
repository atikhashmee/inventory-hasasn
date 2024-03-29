@extends('pdf.app')
@section('content')
<main>
    <br />
    <br />
    <br />
    <br />
    <table width="100%">
        <tr>
            <td width="30%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
            <td width="30%" style="text-align: right; text-decoration: underline">
                {{date('M d, Y', strtotime($created_at))}}
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%">
                <strong>To</strong> <br>
                <strong class="text-uppercase">{{$recipient}}</strong>
                <p>{{$recipient_address}}</p>
            </td>
            <td width="20%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
    </table>

    <br />
    <br />
    <table width="100%">
        <tr>
            <td width="30%">
                <p>Sub: <strong style="text-decoration: underline">{{$subject}}</strong></p>
            </td>
            <td width="50%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
    </table>
    <br />
    <br />
    <br />
    <br />
    <table width="100%" border="1" style="text-align: center;  border-collapse: collapse;">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Description of Items</th>
                <th>Origin</th>
                <th>Unit Price(Taka)</th>
                <th>Quantity</th>
                <th>Total Amount(Taka)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @if (count($items) > 0)
                @foreach ($items as $k => $item)
                    @php
                        $subtotal +=  $item['unit_price'] * $item['quantity'];
                    @endphp
                    <tr>
                        <td>{{++$k}}</td>
                        <td style="text-align: left; padding-left: 20px">
                            <p>{{$item['item_name']}}</p>
                            <span>
                                <strong>Brand: </strong> {{$item['brand']}}
                            </span>
                        </td>
                        <td>{{$item['origin']}}</td>
                        <td>{{$item['quantity']}}
                            {{$item['quantity_unit_id']!=null? $item['unit']['name']: ' Unit' }}
                        </td>
                        <td>{{$item['unit_price']}}</td>
                        <td>{{$item['total_price']}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No item to show</td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>
    <div style="width: 200px; margin-left: auto;">
        <p style="text-align: right; border-bottom: 2px solid #000;">Grand Total</p>
        <p style="text-align: right;">{{$subtotal}} Taka</p>
    </div>
    <p style="text-align: center; text-transform: uppercase"> <strong>In Word(Taka):</strong> {{$amount_in_total_words}}</p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <table style="width: 100%">
        <tr>
            <td>
                {!!$terms_and_con!!}
                {{-- <strong>Terms & Condition</strong>
                <p>1. Delivery: From ready stock</p>
                <p>2. Payment: By cash, cheque</p>
                <p>3. Validity: Our Quotation is valid for 1 month</p>
                <p>4. Vat & Tax: Our price <b>does not include</b> local vat or taxes</p>
                <p>5. Warranty: 1 year free service warranty will be provided</p> --}}
            </td>
        </tr>
    </table>
    <br />
    <br />
    <br />
    @if ($notes)
        <table style="width: 100%">
            <tr>
                <td>
                    <strong>N.B: {{$notes}}</strong>
                </td>
            </tr>
        </table>
    @endif

    <br />
    <br />
    <br />
    <br />
    <table class="signature-table" style="width: 100%">
        <tbody>
            <tr>
                <td width="25%">&nbsp;</td>
                <td width="40%">&nbsp;</td>
                <td width="25%">
                        <p>Thanks & Regards,</p>
                        <br>
                        <br>
                        <br>
                        <strong style="border-top: 1px solid #000; margin-left:auto; text-align:right">Authorized Person</strong> 
                    
                </td>
            </tr>
        </tbody>
    </table>
</main>
@endsection