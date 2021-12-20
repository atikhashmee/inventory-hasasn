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
            width:100%;
            height:200px;
            top:80px;
            margin-bottom:10px;
        }
        .horizonatal-bar {
            position: fixed;
            left: 0;
            top: 0;
            right:0;
            width:100%;
        }
        .horizonatal-bar img {
            width: 120%;
            position: fixed;
            left: 0;
            top: 63px;
            z-index:-1;
        }
        .header-left-section{
            margin-top: 20px;
            width: 40%;
            text-align: center;
            position: relative;
            height:150px;
        }
        .header-right-section{
            text-align: center;
            line-height: 20px;
            padding-top: 10px;
            height:150px;
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
        
        .qr-code {
            width: 80px;
            position: absolute;
            bottom: 0;
            left: 43%;
        }
        .logo {
            position: absolute;
            top: -10%;
            left: 40%;
            width: 120px;
        }
        .logo img, .qr-code img{
            height:75px;
        }
    </style>
</head>
<body>
    
    <div class="invoice-wrapper">
        <div class="horizonatal-bar">
            <img src="{{ asset("assets/img/bar.png") }}" alt="" style="width:100%">
        
        </div>
    
    <table>
        <tr>
            <td class="header-left-section">
                <div class="logo">
                    <img src="{{$shop->image_link}}" alt="">
                    <br>
                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=100x100&chl=stuff" alt="">
                </div>
                
            </td>
            <td class="header-right-section">
                
                <div style="width: 100%; position:relative; left:30%">
                    <h4 class="top-header">{{$shop->name}}</h4>
                    {!!$shop->address!!}
                </div>
            </td>
        </tr>
    </table>
    </div>
    
    
</body>
</html>