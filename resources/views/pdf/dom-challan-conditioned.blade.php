<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <style>
        @page {
            margin: 100px 25px;
        }
        body{
            margin-top: 3cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 2cm;
        }
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 300px;

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            color: rgb(0, 0, 0);
            text-align: center;
            /* line-height: 35px; */
            /* border: 1px solid red; */
        }
        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            /* height: 50px;  */

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            text-align: center;
            /* line-height: 35px; */
            margin-top: 40px;
        }
        footer  img{
            height: 35px;
        }
        
        /* Header footer css end here */
        .doc-type{
            background: #6d5e5e;
            margin: 0 auto;
            text-align: center;
            /* padding: 5px 0; */
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
        table.sum-total tr td{
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
        <header>
            <div style="position: relative">
                <img src="{{ asset("assets/img/bar.png") }}" alt="" style="position: absolute; left: 0; top:10; width: 100%;">
                <table style="width: 100%; position: relative; top: 0;">
                    <tr>
                        <td style="text-align: center; width: 305px; vertical-align: top;">
                            <img src="{{$shop["image_link"]}}" style="position:relative; height: 90px; top: -20px;">
                            <div style="width: 100%; margin-bottom: 3px;"></div>
                            <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=www.meditech.com.bd" alt="">
                        </td>
                        <td style="text-align: center; vertical-align: top;">
                            <div style="width: 100%; left: 0px; top: 40px;">
                                <h4 class="top-header">{{$shop["name"]}}</h4>
                                {!! $shop["address"] !!}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </header>

        <footer>
            @if (!isset($footer_precuation))
                <div class="precausion">
                    <h4>goods once sold will not be taken back</h4>
                </div>
            @endif
            <div class="divider-footer"></div>
    
            <table>
                <tbody>
                    <tr>
                        <td><img src="{{asset('assets/img/pdf-footer-two/1.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/22.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/21.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/19.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/10.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/9.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/5.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/6.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/7.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/24.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/8.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/3.png')}}"  alt=""></td>
                    </tr>
                    <tr>
                        <td><img src="{{asset('assets/img/pdf-footer-two/2.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/11.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/20.jpg')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/12.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/13.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/14.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/15.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/16.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/17.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/18.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/23.png')}}"  alt=""></td>
                        <td><img src="{{asset('assets/img/pdf-footer-two/4.png')}}"  alt=""></td>
                    </tr>
                </tbody>
            </table>
        </footer>

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
</body>
</html>