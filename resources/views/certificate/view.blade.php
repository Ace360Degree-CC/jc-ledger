<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div style="max-width:750px;margin:auto">
        <div style="text-align:right">
            <img style="width:300px;" src="{{ $jc_logo_base64 ?? '' }}">
        </div>

        <div style="text-align:center">
            <h2 style="font-size:22px"> Certificate of Engagement as Business Correspondent</h2>
            <img style="margin:4px auto; width:200px" src="{{ $profile_base64 ?? '' }}"> 
            <div>
            <span style="border:1px solid black; padding:4px; margin:8px auto; font-size:24px;font-weight:bold">{{$ko_id}}</span>
            </div>
        </div>
        <div style="font-size:16px">
            <p>It is hereby notified that <strong>JC Ventures Pvt Ltd</strong> has been engaged as Business
            Correspondent for Bank of India. He/She is authorized to source and market
            banking services and products including authorized third party products from
            BC outlets in {{$zone}} only for Bank of India.
            </p>
            <p>
            <strong>{{$ko_name}}</strong> has been engaged pursuant to the Agreement
            executed on {{$agreement_date}}.
            </p>
        </div>
        <div>
            <p style="font-size:20px">Thank You</p>
            <img width="200px" src="{{ $certificate_footer_base64 ?? '' }}">
        </div>
    </div>    

</body>
</html>