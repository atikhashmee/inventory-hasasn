<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 16px;
        }
        img{
            height: 100%;
            width: 100%;
        }
        .header-container{
            max-height: 20%;
            height: 20%;
        }
        .header-container .top-right-side , .header-container .top-left-side {
            flex-basis: 50% !important;
        }
        .top-left-side {
            height: 150px;
            width: 150px;
        }
        .top-right-side p {
            text-align: justify;
        }
    </style>
</head>
<body>
    <table class="table">
        <tr>
            <td>
                <div class="top-left-side">
                    <img src="{{asset('assets/img/logo.png')}}" alt="">
                </div>
            </td>
            <td>
                <div class="top-right-side">
                    <h4 class="text-uppercase">Midland</h4>
                    <p>15/2, Topkhna Road, BMA Bhaban(1st floor), G.P.O Box-2744</p>
                    <p>Dhaka 1000, Bangladesh. TEL: 9585868, FAX: 880-2-9569163</p>
                    <p>Mobile: 01755591795</p>
                    <p>Email: meditechash@gmail.com, ahmidiland@gmail.com</p>
                </div>
            </td>
        </tr>
    </table>
    <table class="table" style="min-height: 80vh">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td colspan="2">Larry the Bird</td>
            <td>@twitter</td>
          </tr>
        </tbody>
    </table>
    <table class="table">
        <tr>
            <td>
                <img src="" alt="">
            </td>
        </tr>
    </table>
</body>
</html>