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
            height: 19px;
        }
        
        /* Header footer css end here */
        
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
</body>
</html>