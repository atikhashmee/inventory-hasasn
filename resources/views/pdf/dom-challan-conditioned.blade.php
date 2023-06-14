@extends('pdf.app')
@section('content')
      <!-- Wrap the content of your PDF inside a main tag -->
      <main>
        <div class="doc-type">
            <h2>CHALLAN</h2>
        </div>

        <br />
        <table class="invoice-info">
            <tr>
                <td width="50%" style="line-height: 12px">
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
                    <th>Quantity</th>
                    <th>Product Type</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{$quantity}} {{$unit['name'] ?? ''}}</td>
                    <td>{{$product_type}}</td>
                </tr>
            </tbody>
        </table>
        @if ($total_payable)
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <table class="summer-table">
                <tbody>
                    <tr>
                        <td width="40%">&nbsp;</td>
                        <td width="20%">&nbsp;</td>
                        <td width="30%">
                            <table class="sum-total">
                                <tr>
                                    <td><strong>Total Payable(Taka)</strong></td>
                                    <td>{{number_format(intval($total_payable), 2, '.', ',')}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
        <br />
        <p style="text-align: center; text-transform: uppercase"> <strong>In Word(Taka):</strong> {{numberToWord(($total_payable))}}</p>
        <br />
        <br />
        <br />
        <br />
        <table class="signature-table">
            <tbody>
                <tr>
                    <td width="25%">&nbsp;</td>
                    <td width="40%">&nbsp;</td>
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
        @if ($challan_note)
            <table style="width: 100%">
                <tr>
                    <td>
                        <strong>N.B: {{$challan_note}}</strong>
                    </td>
                </tr>
            </table>
        @endif
        @if ($challan_type)
            <table style="width: 100%">
                <tr>
                    <td style="text-align:center">
                    <strong style="font-size: 18px">Challan Type: {{$challan_type}}</strong> 
                    </td>
                </tr>
            </table>
        @endif

    </main>
@endsection