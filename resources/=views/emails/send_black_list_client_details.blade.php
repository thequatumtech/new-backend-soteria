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
<div class="">
    <div class="content">
        <p><strong>{{__('messages.black_lists.name')}} : </strong><span>{{$full_name}}</span></p>
        <p><strong>{{__('messages.black_lists.client_mobile')}} : </strong><span>{{$mobile_no}}</span></p>
        <p><strong>{{__('messages.black_lists.client_email')}} : </strong><span>{{$email_id}}</span></p>
        <p><strong>{{__('messages.black_lists.client_national_id')}} : </strong><span>{{$national_id_number}}</span></p>
        <p><strong>{{__('messages.black_lists.insurance_company_list')}} : </strong><span>{{$insurance_companies_name}}</span></p>
        <p><strong>{{__('messages.black_lists.insurance_type_list')}} : </strong><span>{{$insurance_types_name}}</span></p>
        <p><strong>{{__('messages.black_lists.gross_premium_paid')}} : </strong><span>{{$total_gross_premium_paid}}</span></p>
        <p><strong>{{__('messages.black_lists.net_premium_paid')}} : </strong><span>{{$total_net_premium_paid}}</span></p>
        <p><strong>{{__('messages.black_lists.insured_period')}} : </strong><span>{{$insured_period}}</span></p>
        <div class="row">
            <div class="col-lg-3 col-12">
                <p><strong>{{__('messages.black_lists.insurance_type_cannot_purchase')}} : </strong></p>
            </div>
            <div class="col-lg-9 col-12">
                <span>{{$insurance_type_cannot_purchase}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-12">
                <p><strong>{{__('messages.black_lists.reason')}} : </strong>
                </p>
            </div>
            <div class="col-lg-10 col-12">
                <span>{!! $black_list_reason !!}</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>

