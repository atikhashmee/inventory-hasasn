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
            border: 1px solid #fff;
            width: 30%;
            text-align: center;
        }
        .header-left-section img{
            width: 100%;
        }
        .header-right-section{
            text-align: center;
            line-height: 20px;
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
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="header-left-section">
                <img src="{{$shop->image_link}}" alt="">
            </td>
            <td width="20%">&nbsp;</td>
            <td class="header-right-section">
                <h4 class="top-header">{{$shop->name}}</h4>
                <p>15/2, Topkhna Road, BMA Bhaban(1st floor), G.P.O Box-2744</p>
                <p>Dhaka 1000, Bangladesh. TEL: 9585868, FAX: 880-2-9569163</p>
                <p>Mobile: 01755591795</p>
                <p>E-mail: meditechash@gmail.com, ahmidiland@gmail.com</p>
                <p class="website-text">www.meditech.com.bd</p>
            </td>
        </tr>
    </table>
</body>
</html>