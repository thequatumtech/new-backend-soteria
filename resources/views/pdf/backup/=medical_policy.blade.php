<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data->insurance_type_status == 1 ? 'Individual' : 'Family'}} Medical Insurance Policy</title>
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
        table-layout: fixed;
    }

    .signatures td {
        width: 50%;
        padding: 15px;
        border: 1px solid #ccc;
        font-weight: bold;
        background-color: #e0e0e0;
        vertical-align: top;
    }

    .signatures img {
        width: 150px !important;
        height: 70px !important;
        max-width: 150px !important;
        max-height: 70px !important;
        margin: 0 auto;
    }

    .signature-image {
        width: 150px !important;
        height: 70px !important;
        max-width: 150px !important;
        max-height: 70px !important;
        margin: 0 auto;
    }

    .insured-signature-table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .insured-signature-table td {
        width: 100%;
        padding: 0;
        border: 0;
        background-color: transparent;
        text-align: center;
        vertical-align: middle;
    }

    .insured-signature-table .insured-title {
        padding-bottom: 8px;
    }

    .insured-signature-table .insured-image {
        height: 80px;
        padding-bottom: 8px;
    }

    .insured-signature-table .authorized-title {
        padding-top: 5px;
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
            <img src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo)}}" alt="{{ $data->company_name }}" class="logo">
            <h1>{{ $data->plan_name }}</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{$data->police_no}})</td>
            </tr>
            <tr>
                <td><strong>Policy Type</strong></td>
                <td>{{$data->insurance_type_status == 1 ? 'Individual' : 'Family'}} Medical Insurance Policy Medical Insurance</td>
                <!-- <td>{{$data->plan_name}}</td> -->
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{$data->first_name}} {{$data->last_name}} {{$data->third_name}}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{date('m-d-Y', strtotime($data->inception_date))}}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{date('m-d-Y', strtotime($data->expiry_date))}}</td>
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
            <h4>Net Premium </h4>
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
                <td>
                    Insurer

                    <div>
                        <img
                            src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp)}}"
                            alt=""
                            width="150"
                            height="70"
                            class="signature-image">
                    </div>
                </td>

                <td>
                    <table class="insured-signature-table">
                        <tr>
                            <td class="insured-title">
                                Insured
                            </td>
                        </tr>

                        {{-- User Signature --}}
                        @if(!empty($data->user_signature) && file_exists(public_path($data->user_signature)))
                        <tr>
                            <td class="insured-image">
                                <img
                                    src="{{ public_path($data->user_signature) }}"
                                    alt="User Signature"
                                    width="150"
                                    height="70"
                                    class="signature-image">
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td class="insured-image">
                                <img
                                    src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature)}}"
                                    alt="Authorized Signature"
                                    width="150"
                                    height="70"
                                    class="signature-image">
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td class="authorized-title">
                                Authorized Signature
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>
    </div>

</html>