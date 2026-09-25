<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Medical Insurance Policy</title>
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
            <img src="{{public_path('insurance/'.$data->insurance_company_id.'/'.$data->logo)}}" alt="{{ $data->company_name }}" class="logo">
            <h1>{{$data->plan_name}}</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{$data->police_no}})</td>
            </tr>
            <tr>
                <td><strong>Policy Type</strong></td>
                <td>{{$data->plan_name}}</td>
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{$data->first_name}} {{$data->last_name}} {{$data->third_name}} {{$data->family_name}}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->inception_date))}}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{date('m-d-Y',strtotime($data->expiry_date))}}</td>
            </tr>
            <!-- <tr>
                <td><strong>Geographical Coverage</strong></td>
                <td></td>
            </tr> -->
            <tr>
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr>
            <!-- <tr>
                <td><strong>TPA</strong></td>
                <td>NatHealth</td>
            </tr> -->
        </table>

        <div class="policy-summary">
            <h4>Policy Summary</h4>

            <table class="policy-details">
                <tr>
                    @isset($plan->limit)<td>Limit</td>@endisset
                    @isset($plan->net_premium_amount)<td>Net Premium</td>@endisset
                    @isset($plan->fees)<td>Fees ({{ $plan->fees->fees ?? $plan->fees }}%)</td>@endisset
                    @isset($plan->stamps)<td>Stamps ({{ $plan->fees->stamps ?? $plan->stamps }}%)</td>@endisset
                    @isset($plan->sales_tax)<td>Sales Tax ({{ $plan->fees->sales_tax ?? $plan->sales_tax }}%)</td>@endisset

                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                    <td>Contribution to Guarantee Fund (CBJ) ({{ $plan->cbj }}%)</td>
                    @endif

                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                    <td>Sales Tax on CBJ Contribution Fund ({{ $plan->sales_tax_cbj }}%)</td>
                    @endif

                    @isset($plan->gross_premium_amount)<td>Gross Premium</td>@endisset
                </tr>

                <tr>
                    @isset($plan->limit)<td>{{ number_format((float)$plan->limit, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->net_premium_amount)<td>{{ number_format((float)$plan->net_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->fees_amount)<td>{{ number_format((float)$plan->fees_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->stamps_amount)<td>{{ number_format((float)$plan->stamps_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->sales_tax_amount)<td>{{ number_format((float)$plan->sales_tax_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset

                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                    <td>{{ number_format((float)$plan->cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                    @endif

                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                    <td>{{ number_format((float)$plan->sales_tax_cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                    @endif

                    @isset($plan->gross_premium_amount)
                    <td>{{ number_format((float)$plan->gross_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                    @endisset
                </tr>
            </table>
        </div>


        @if(!empty($plan->policy_covers) && count($plan->policy_covers) > 0)

        <h4>Policy Covers</h4>

        <table class="policy-details">
            <tr>
                <td><strong>Name of Cover</strong></td>
                <td><strong>Limit</strong></td>
            </tr>

            @foreach($plan->policy_covers as $cover)
            <tr>
                <td>{{ $cover->cover_name }}</td>
                <td>
                    {{ $cover->cover_limit }}
                    <!-- @if($cover->cover_limit_type == '2') % @endif -->
                </td>
            </tr>
            @endforeach
        </table>

        @endif
        @if(!empty($plan->additional_benefits) && count($plan->additional_benefits) > 0)

        <h4>Additional Benefits</h4>

        <table class="policy-details">
            <tr>
                <td><strong>Name of Benefit</strong></td>
                <td><strong>Limit</strong></td>
            </tr>

            @foreach($plan->additional_benefits as $benefit)
            <tr>
                <td>{{ $benefit->benefit_name }}</td>
                <td>
                    {{ $benefit->benefit_limit }}
                    <!-- @if($benefit->benefit_limit_type == '2') % @endif -->
                </td>
            </tr>
            @endforeach
        </table>

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