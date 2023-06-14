<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{date("Y-m-d")}}-Invoice</title>

    <style>
        @page {
            margin: 100px 25px;
        }
        body{
            margin-top: 4cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 2cm;
        }
        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            /* height: 300px; */

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
        main {
            /* background-color: blue; */
        }
        
        /* Header footer css end here */
        .doc-type{
            background: #6d5e5e;
            margin: 0 auto;
            text-align: center;
            /* padding: 1px 0; */
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

</head>

<header>
    <div style="position: relative;">
        <img src="{{ asset("assets/img/bar.png") }}" alt="" style="position: absolute; left: 0; top:10; width: 100%;">
        <table style="width: 100%; position: relative; top: 0;">
            <tr>
                <td style="text-align: center; width: 250px; vertical-align: top;">
                    <img src="{{$shop["image_link"]}}" style="position:relative; height: 90px; top: -20px; left: 20px">
                    <div style="width: 100%;"></div>
                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=www.meditech.com.bd" style="position:relative; height: 90px; top: -10px; left: 20px">
                </td>
                <td style="vertical-align: top;">
                    <div style="text-align: center; margin-top: 18px">
                        <h4 style="padding: 0; margin: 0; text-transform: uppercase; font-size: 25px; color: red;">{{$shop["name"]}}</h4>
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
<body>
    @yield('content')
</body>
</html>