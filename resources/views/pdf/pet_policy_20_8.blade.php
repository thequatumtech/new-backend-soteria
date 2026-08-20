<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets Insurance Policy</title>
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
            <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo) }}" alt="{{ $data->company_name }}" class="logo">
            <h1>{{$data->plan_name}}</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{$data->police_no}})</td>
                <td><strong>Policy Type</strong></td>
                <td>{{$data->plan_name}}</td>
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td colspan="3">{{$data->first_name}} {{$data->last_name}} {{$data->third_name}} {{$data->family_name}}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->inception_date))}}</td>
                <td><strong>Expiry Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->expiry_date))}}</td>
            </tr>
            <tr>
                <td><strong>Pets Name</strong></td>
                <td>{{$data->pets_name}}</td>
                <td><strong>Birth Date</strong></td>
                <td>{{$data->pets_dob}}</td>
            </tr>
            <tr>
                <td><strong>Pets Type</strong></td>
                <td>{{$data->pets_type==1 ? 'Dog' : 'Cat'}}</td>
                <td><strong>Nationality No</strong></td>
                <td>{{$data->nationality_no}}</td>
            </tr>
            <tr>
                <td><strong>Gender</strong></td>
                <td>{{$data->gender}}</td>
                <td><strong>Breed</strong></td>
                <td>{{$data->breed}}</td>
            </tr>
            <tr>
                <td><strong>Plan Name</strong></td>
                <td>{{$data->plan_name}}</td>
                <td><strong>Insurance Company</strong></td>
                <td>{{$data->company_name}}</td>
            </tr>
        </table>
        @php
        // Removed DB query — $abbr should be passed from controller
        $abbr = $abbr ?? 'JOD';

        $columns = [];
        if(!empty($purchase->policy_plan_limit)) $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;
        if(!empty($purchase->net_premium)) $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . ($data->fees > 0 ? ' (' . $data->fees . '%)' : '');
        if(!empty($purchase->fees)) $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . ($data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');
        if(!empty($purchase->stamps)) $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . ($data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');
        if(!empty($purchase->sales_tax)) $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');
        if(!empty($purchase->cbj)) $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($data->sales_tax_cbj > 0 ? ' (' . $data->sales_tax_cbj . '%)' : '');
        if(!empty($purchase->sales_tax_cbj)) $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;

        if(!empty($purchase->gross_premium)) $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;
        // if(!empty($purchase->commission_amount)) $columns['Commission Amount'] = number_format($purchase->commission_amount, 2) . ' ' . $abbr;
        @endphp

        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <table class="policy-details">
                <tr>
                    @foreach($columns as $label => $value)
                    <td>{{ $label }}</td>
                    @endforeach
                </tr>
                <tr>
                    @foreach($columns as $label => $value)
                    <td>{{ $value }}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif

        @if(!empty($plan->policy_covers) && count($plan->policy_covers) > 0)
        <div class="policy-summary">
            <h4>Policy Covers</h4>

            <table class="policy-details">
                <tr>
                    <td><strong>Name of Cover</strong></td>
                    <td><strong>Limit</strong></td>
                    <td><strong>Deductible</strong></td>
                    <td><strong>Rate</strong></td>
                    <td><strong>Premium</strong></td>
                </tr>

                @foreach($plan->policy_covers as $cover)
                <tr>
                    <td>{{ $cover->cover_name }}</td>
                    <td>{{ $cover->cover_limit }}</td>
                    <td>{{ $cover->cover_deductible }}</td>
                    <td>{{ $cover->cover_rate }}</td>
                    <td>{{ number_format($cover->cover_premium, 2) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        <table class="signatures">
            <tr>
                <td>{{$data->company_name}}</td>
                <td>Policy Holder / {{$data->first_name}} {{$data->last_name}} {{$data->third_name}} {{$data->family_name}}</td>
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