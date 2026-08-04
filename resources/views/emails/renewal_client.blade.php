<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Your Policy is About to Expire</title>

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
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .header {
            background-color: #0073e6;
            color: #ffffff;
            text-align: center;
            padding: 20px 15px;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f4f4f4;
            color: #777777;
            font-size: 14px;
        }

        ul {
            padding-left: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0073e6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            Policy Expiry Reminder
        </div>
        <div class="content">

            {{--<p>Dear {{ $client->first_name ?? 'Valued Client' }},</p>--}}
            <p>Dear {{ $client->full_name ?? 'Valued Client' }},</p>

            

            <p>
                This is a reminder that your policy
                <strong>{{ $policy->plan_name ?? $policy->policy_no }}</strong>
                (Policy No: <strong>{{ $policy->policy_no }}</strong>)
                will expire on
                <strong>{{ \Carbon\Carbon::parse($policy->expiry_date)->toFormattedDateString() }}</strong>.
                That's <strong>{{ $daysLeft }}</strong> day(s) remaining.
            </p>

            <p>Please contact us to renew your policy or if you have any questions.</p>

            <h3>Policy Details:</h3>
            <ul>
                <li><strong>Policy No:</strong> {{ $policy->policy_no }}</li>
                <li><strong>Plan:</strong> {{ $policy->plan_name }}</li>
                <li><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($policy->expiry_date)->toDateString() }}</li>
            </ul>

            <p>Regards,<br><strong>{{ env('APP_NAME') }}</strong></p>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
        </div>

    </div>

</body>

</html>