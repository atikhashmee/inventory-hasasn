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
        body {
            position:relative;
            width:100%;
            height:100%;
        }
        .invoice-wrapper {
            position:relative;
            width:890px;
            height:200px;
            top:50px;
            margin-bottom:10px;
        }        
        .top-header{
            font-size: 25px;
            text-transform: uppercase;
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <img src="{{ asset("assets/img/bar.png") }}" alt="" style="width: 100%;">
    
        <table style="width: 830px; position: relative; top: -120px;">
            <tr>
                <td style="text-align: center; width: 340px; vertical-align: top;">
                    <img src="{{$shop->image_link}}" style="height: 90px;">
                    <div style="width: 100%; margin-bottom: 8px;"></div>
                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=www.meditech.com.bd" alt="">
                </td>
                <td style="text-align: center; vertical-align: top;">
                    <div style="width: 100%; position: relative; top: 40px;">
                        <h4 class="top-header">{{$shop->name}}</h4>
                        {!! $shop->address !!}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    
</body>
</html>