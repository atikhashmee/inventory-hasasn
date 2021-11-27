<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wkpdf Header</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .header-left-section{
            margin-top: 20px;
            width: 40%;
            text-align: center;
            position: relative;
        }
        .header-right-section{
            text-align: center;
            line-height: 20px;
            padding-top: 10px;
        }
        .top-header{
            font-size: 25px;
            text-transform: uppercase;
            color: red;
            margin-bottom: 10px;
        }
        .website-text {
            color: red;
            font-size: 18px; 
        }
        .horizonatal-bar {
            position: fixed;
            left: 0;
            top: 0%;
        }
        .qr-code {
            width: 80px;
            position: absolute;
            bottom: -5%;
            left: 43%;
        }
        .logo {
            position: absolute;
            top: -10%;
            left: 40%;
            width: 120px;
        }
        .logo img, .qr-code img{
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="horizonatal-bar">
        <img src="{{ asset("assets/img/bar.png") }}" alt="">
    </div>
    <table>
        <tr>
            <td class="header-left-section">
                <div class="logo">
                    <img src="{{$shop->image_link}}" alt="">
                </div>
                <div class="qr-code">
                    {{-- <img src="{{ isset($qrCode) ? $qrCode->getDataUri() : null }}" alt=""> --}}
                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=stuff" alt="">
                </div>
            </td>
            <td width="20%">
            </td>
            <td class="header-right-section">
                <h4 class="top-header">{{$shop->name}}</h4>
                {!!$shop->address!!}
                {{-- <p>15/2, Topkhna Road, BMA Bhaban(1st floor), G.P.O Box-2744</p>
                <p>Dhaka 1000, Bangladesh. TEL: 9585868, FAX: 880-2-9569163</p>
                <p>Mobile: 01755591795</p>
                <p>E-mail: meditechash@gmail.com, ahmidiland@gmail.com</p>
                <p class="website-text">www.meditech.com.bd</p> --}}
            </td>
        </tr>
    </table>
</body>
</html>