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
            vertical-align:top;
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
            <img style="float:left" height="60px" src="{{ $boi_logo_base64 ?? '' }}">
            <img style="float:right" height="60px" src="{{ $jc_logo_base64 ?? '' }}">
            <div style="height:60px;margin-bottom:20px"></div>
        </div>
        <div style="text-align:center">
            <img style="margin:8px auto; width: 180px" src="{{ $profile_base64 ?? '' }}">
        </div>
        <table style="width:100%">
            <tbody>
                <tr>
                    <td>BC name:</td>
                    <td>{{$bc_name ?? ''}}</td>
                </tr>
                <tr>
                    <td>BC Id:</td>
                    <td>{{$bc_id ?? ''}}</td>
                </tr>
                <tr>
                    <td>BC Location:</td>
                    <td>{{$bc_location ?? ''}}</td>
                </tr>
                <tr>
                    <td>Branch code:</td>
                    <td>{{$branch_code ?? ''}}</td>
                </tr>
                <tr>
                    <td>Branch Name:</td>
                    <td>{{$branch_name ?? ''}}</td>
                </tr>
                <tr>
                    <td>BC Mobile Number:</td>
                    <td>{{$bc_mobile ?? ''}}</td>
                </tr>
                <tr >
                    <td style="padding-bottom:0px;">Authorised Signatory</td>
                    <td style="padding-bottom:0px;">BC Signature</td>
                    
                </tr>

                <tr>
                    <td><img style="margin:8px auto; width: 180px" src="{{ $jc_signature_base64 ?? '' }}"></td>
                    <td><img style="margin:8px auto; width: 180px" src="{{ $signature_base64 ?? '' }}"></td>
                </tr>

            </tbody>
        </table>
        </div>
    </div>

    </div>

    <!-- <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script> -->
</body>
</html>