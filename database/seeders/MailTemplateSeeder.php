<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MailTemplate;

class MailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $clientHtml = <<<'HTML'
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

            <p>Dear [CLIENT_NAME],</p>

            <p>
                This is a reminder that your policy
                <strong>[PLAN_NAME]</strong>
                (Policy No: <strong>[POLICY_NO]</strong>)
                will expire on
                <strong>[EXPIRY_DATE_FORMATTED]</strong>.
                That's <strong>[DAYS_LEFT]</strong> day(s) remaining.
            </p>

            <p>Please contact us to renew your policy or if you have any questions.</p>

            <h3>Policy Details:</h3>
            <ul>
                <li><strong>Policy No:</strong> [POLICY_NO]</li>
                <li><strong>Plan:</strong> [PLAN_NAME]</li>
                <li><strong>Expiry Date:</strong> [EXPIRY_DATE_SHORT]</li>
            </ul>

            <p>Regards,<br><strong>[APP_NAME]</strong></p>
        </div>

        <div class="footer">
            &copy; [YEAR] [APP_NAME]. All rights reserved.
        </div>

    </div>

</body>

</html>
HTML;

        $agentHtml = <<<'HTML'
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Your Client's Policy is About to Expire</title>

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
            line-height: 1.6;
            font-size: 16px;
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
            Client Policy Expiry Reminder
        </div>

        <div class="content">

            <p>Dear [AGENT_NAME],</p>

            <p>
                Your client <strong>[CLIENT_FIRST_NAME] [CLIENT_SURNAME]</strong> has a policy
                <strong>[PLAN_NAME]</strong>
                (Policy No: <strong>[POLICY_NO]</strong>)
                that will expire on
                <strong>[EXPIRY_DATE_FORMATTED]</strong>.
                There are <strong>[DAYS_LEFT]</strong> day(s) remaining.
            </p>

            <p>Please follow up with the client regarding their policy renewal.</p>

            <h3>Policy Details:</h3>
            <ul>
                <li><strong>Client:</strong> [CLIENT_FIRST_NAME] [CLIENT_SURNAME]</li>
                <li><strong>Policy No:</strong> [POLICY_NO]</li>
                <li><strong>Plan:</strong> [PLAN_NAME]</li>
                <li><strong>Expiry Date:</strong> [EXPIRY_DATE_SHORT]</li>
            </ul>

            <p>Regards,<br><strong>[APP_NAME]</strong></p>
        </div>

        <div class="footer">
            &copy; [YEAR] [APP_NAME]. All rights reserved.
        </div>

    </div>

</body>

</html>
HTML;

        MailTemplate::updateOrCreate(['type' => 'client'], ['content' => $clientHtml]);
        MailTemplate::updateOrCreate(['type' => 'agent'],  ['content' => $agentHtml]);
    }
}
