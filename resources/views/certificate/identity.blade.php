<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Identity</title>
</head>
<body>
    <style>
        *{
            padding:0;
            margin:0;
        }
        td{
            width:50%;
            font-size:20px;
            font-weight:600;
            padding-left:4px;
            padding-bottom:15px;
        }
        .main-box{
            min-height:100vh;
            width:100%;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
        }
    </style>
    
    <div class="main-box">

    <div style="width:450px;margin:auto;border:1px solid grey;border-radius:8px;padding:8px">
        <div id="id-box" style="width:100%">
        <div class="">
            <img style="float:left" height="60px" src="{{asset('assets/images/logos/boi-logo.png')}}">
            <img style="float:right" height="60px" src="{{asset('assets/images/logos/jc-logo.png')}}">
            <div style="height:60px;margin-bottom:20px"></div>
        </div>
        <div style="text-align:center">
            <img style="margin:8px auto; width: 180px" src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png">
        </div>
        <table style="width:100%">
            <tbody>
                <tr>
                    <td>BC name:</td>
                    <td>Rajendra baburoa Patil</td>
                </tr>
                <tr>
                    <td>BC Id:</td>
                    <td>111652254</td>
                </tr>
                <tr>
                    <td>BC Location:</td>
                    <td>Karanjpen</td>
                </tr>
                <tr>
                    <td>Branch code:</td>
                    <td>0968</td>
                </tr>
                <tr>
                    <td>Branch Name:</td>
                    <td>Kotoli</td>
                </tr>
                <tr>
                    <td>BC Mobile Number:</td>
                    <td>Kotoli</td>
                </tr>
                <tr>
                    <td>Authorised Signatory</td>
                    <td>BC Signature</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

    </div>

</body>
</html>