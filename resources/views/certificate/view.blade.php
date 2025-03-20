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
            <img style="width:300px;" src="{{asset('assets/images/certificates/jc-certificate-logo.PNG')}}">
        </div>

        <div style="text-align:center">
            <h2 style="font-size:28px"> Certificate of Engagement as Business Correspondent</h2>
            <img style="margin:8px auto; width:200px" src="{{ asset('storage/csp/profile/'.$profile) }}"> 
            <div>
            <span style="border:1px solid black; padding:4px; margin:8px auto; font-size:24px;font-weight:bold">{{$ko_id}}</span>
            </div>
        </div>
        <div style="font-size:24px">
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
            <p style="font-size:24px">Thank You</p>
            <img width="200px" src="{{asset('assets/images/certificates/business-correspondent.PNG')}}">
        </div>
    </div>    

</body>
</html>