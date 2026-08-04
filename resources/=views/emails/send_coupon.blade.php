<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offer Just for You!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #0073e6;
            color: #ffffff;
        }

        .content {
            padding: 20px;
            text-align: left;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
            color: #555555;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0073e6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Special Offer Just for You!</h1>
    </div>
    <div class="content">
        <p>Dear <strong>{{$name}}</strong>,</p>
        <p>We hope this message finds you well!</p>
        <p>As a token of our appreciation for being a valued customer, we are excited to offer you an exclusive
            discount. Enjoy {{$discount}}% off your next purchase of {{$insurance_type}} from {{$insurance_company}}
            with us!</p>
        <p>Your Discount Code: <strong>{{$coupon_code}}</strong></p>
        <p>This discount code is valid from <strong>{{$effective_date}}</strong> to <strong>{{$expiry_date}}</strong>.
        </p>

        @if(!empty($description))
            <p>A special message just for you from our team
                <strong>{!! $description !!}</strong>
            </p>
        @endif

        <p>Thank you for choosing <strong>{{env('APP_NAME')}}</strong>. We look forward to serving you again soon!</p>
        <p>Best Regards,</p>
        <p>{{env('APP_NAME')}}</p>
    </div>
{{--
    <div class="footer">
    </div>
--}}
</div>
</body>
</html>

