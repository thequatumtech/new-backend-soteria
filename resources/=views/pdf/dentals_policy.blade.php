<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Insurance Policy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .policy-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border: 1px solid #ddd;
    }

    .policy-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .policy-header .logo {
        max-width: 150px;
        margin-bottom: 10px;
    }

    .policy-header h1 {
        font-size: 18px;
        margin: 0;
        font-weight: normal;
    }

    .policy-details {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .policy-details td {
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f0f0f0;
        font-size: 14px;
    }

    .policy-details td:first-child {
        font-weight: bold;
        background-color: #e0e0e0;
    }

    .policy-summary {
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .signatures {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        text-align: center;
    }

    .signatures td {
        padding: 15px;
        border: 1px solid #ccc;
        font-weight: bold;
        background-color: #e0e0e0;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #666;
    }

</style>
<body>
    <div class="policy-container">
        <header class="policy-header">
            <img src="{{public_path('insurance/'.$data->insurance_company_id.'/'.$data->logo)}}" alt="Arabia Insurance Jordan" class="logo">
            <h1>Dental insurance contract</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{$data->police_no}})</td>
            </tr>
            <tr>
                <td><strong>Policy Type</strong></td>
                <td>Dental Insurance</td>
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{$data->first_name}} {{$data->last_name}} {{$data->third_name}}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->inception_date))}}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->expiry_date))}}</td>
            </tr>
            <tr>
                <td><strong>Geographical Coverage</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr>
            <tr>
                <td><strong>TPA</strong></td>
                <td>NatHealth</td>
            </tr>
        </table>
        <div class="policy-summary">
            <h4>Net Premium	</h4>
            <table class="policy-details">
                <tr>
                    <td>Limit</td>
                    <td>Net Premium</td>
                    <td>Fees</td>
                    <td>Stamps</td>
                    <td>Sales Tax</td>
                    <td>Gross Premium</td>
                </tr>
                <tr>
                    <td>{{$data->limit}}</td>
                    <td>{{$data->net_premium}}</td>
                    <td>{{$data->fees}}</td>
                    <td>{{$data->stamps}}</td>
                    <td>{{$data->sales_tax}}</td>
                    <td>{{$data->gross_premium}}</td>
                </tr>
            </table>
        </div>
        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        <table class="signatures">
            <tr>
                <td>{{$data->company_name}}</td>
                <td>Policy Holder / {{$data->first_name}} {{$data->last_name}} {{$data->third_name}}</td>
            </tr>
            <tr>
                <td>Insurer <img src="{{public_path('insurance/'.$data->insurance_company_id.'/'.$data->company_stamp)}}" alt="" class="logo"></td>
                <td>Insured <img src="{{public_path('insurance/'.$data->insurance_company_id.'/'.$data->authorized_signature)}}" alt="" class="logo"></td>
            </tr>
        </table>

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>
    </div>
</html>
