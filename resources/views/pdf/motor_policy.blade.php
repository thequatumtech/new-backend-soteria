<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Insurance Policy</title>
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
            <h1>Motor insurance contract</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{$data->police_no}})</td>
            </tr>
            <tr>
                <td><strong>Policy Type</strong></td>
                <td>{{ $data->plan_name }}</td>
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
            <h4>Premium Summary</h4>
            <table class="policy-details">
                <tr>
                    @isset($plan->limit)<td>Limit</td>@endisset
                    @isset($plan->net_premium_amount)<td>Net Premium</td>@endisset
                    @isset($plan->fees->fees)<td>Fees ({{ $plan->fees->fees }}%)</td>@endisset
                    @isset($plan->fees->stamps)<td>Stamps ({{ $plan->fees->stamps }}%)</td>@endisset
                    @isset($plan->fees->sales_tax)<td>Sales Tax ({{ $plan->fees->sales_tax }}%)</td>@endisset
                    @if(isset($plan->fees->cbj) && $plan->fees->cbj > 0)<td>Contribution to Guarantee Fund (CBJ) ({{ $plan->fees->cbj }}%)</td>@endif
                    @if(isset($plan->fees->sales_tax_cbj) && $plan->fees->sales_tax_cbj > 0)<td>Sales Tax on CBJ Contribution Fund ({{ $plan->fees->sales_tax_cbj }}%)</td>@endif

                    @isset($plan->gross_premium_amount)<td>Gross Premium</td>@endisset
                </tr>
                <tr>
                    @isset($plan->limit)<td>{{ number_format((float)$plan->limit, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->net_premium_amount)<td>{{ number_format((float)$plan->net_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->fees->fees)<td>{{ number_format((float)($plan->fees_amount ?? 0), 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->fees->stamps)<td>{{ number_format((float)($plan->stamps_amount ?? 0), 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->fees->sales_tax)<td>{{ number_format((float)($plan->sales_tax_amount ?? 0), 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @if(isset($plan->fees->cbj) && $plan->fees->cbj > 0)<td>{{ number_format((float)($plan->cbj_amount ?? 0), 2) }} {{ $abbr ?? 'JOD' }}</td>@endif
                    @if(isset($plan->fees->sales_tax_cbj) && $plan->fees->sales_tax_cbj > 0)<td>{{ number_format((float)($plan->sales_tax_cbj_amount ?? 0), 2) }} {{ $abbr ?? 'JOD' }}</td>@endif
                    @isset($plan->gross_premium_amount)<td>{{ number_format((float)$plan->gross_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                </tr>
            </table>
        </div>


        <!-- <div class="policy-summary">
            <h4>Policy Summary</h4>
            <table class="policy-details">
                <tr>
                    @if(!empty($plan->limit))<td>Limit</td>@endif
                    @if(!empty($plan->fees->net_premium))<td>Net Premium</td>@endif
                    @if(!empty($plan->fees->fees))<td>Fees</td>@endif
                    @if(!empty($plan->fees->stamps))<td>Stamps</td>@endif
                    @if(!empty($plan->fees->sales_tax))<td>Sales Tax</td>@endif
                    @if(!empty($plan->fees->gross_premium))<td>Gross Premium</td>@endif
                </tr>
                <tr>
                    @if(!empty($plan->limit))<td>{{ $plan->limit }}</td>@endif
                    @if(!empty($plan->fees->net_premium))<td>{{ $plan->fees->net_premium }}%</td>@endif
                    @if(!empty($plan->fees->fees))<td>{{ $plan->fees->fees }}%</td>@endif
                    @if(!empty($plan->fees->stamps))<td>{{ $plan->fees->stamps }}%</td>@endif
                    @if(!empty($plan->fees->sales_tax))<td>{{ $plan->fees->sales_tax }}%</td>@endif
                    @if(!empty($plan->fees->gross_premium))<td>{{ $plan->fees->gross_premium }}%</td>@endif
                </tr>
            </table>
        </div> -->

        @if(isset($plan->policy_covers) && $plan->policy_covers->count() > 0)

        <div class="policy-summary">
            <h4>Policy Covers</h4>

            <table class="policy-details">
                <thead>
                    <tr>
                        <td><strong>Name of Cover</strong></td>
                        <td><strong>Limit</strong></td>
                    </tr>
                </thead>

                <tbody>
                    @foreach($plan->policy_covers as $cover)
                    <tr>
                        <td>{{ $cover->cover_name }}</td>

                        <td>
                            <!-- @if($cover->cover_limit_type == 1) -->
                            <!-- {{ number_format((float)$cover->cover_limit, 2) }} -->
                            <!-- @else -->
                            {{ $cover->cover_limit }}
                            <!-- @endif -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <br>
        @endif
        @if(isset($plan->additional_benefits) && $plan->additional_benefits->count() > 0)

        <div class="policy-summary">
            <h4>Additional Benefits</h4>

            <table class="policy-details">
                <thead>
                    <tr>
                        <td><strong>Name of Benefit</strong></td>
                        <td><strong>Limit</strong></td>
                        <td><strong>Deductible</strong></td>
                    </tr>
                </thead>

                <tbody>
                    @foreach($plan->additional_benefits as $benefit)
                    <tr>
                        <td>{{ $benefit->benefit_name }}</td>

                        <td>
                            <!-- @if($benefit->benefit_limit_type == 1) -->
                            <!-- {{ number_format((float)$benefit->benefit_limit, 2) }} -->
                            <!-- @else -->
                            {{ $benefit->benefit_limit }}
                            <!-- @endif -->
                        </td>

                        <td>
                            <!-- @if($benefit->benefit_deductible_type == 1) -->
                            <!-- {{ number_format((float)$benefit->benefit_deductible, 2) }} -->
                            <!-- @else -->
                            {{ $benefit->benefit_deductible }}
                            <!-- @endif -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
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
        <!-- <tr>
            <td style="text-align:center; vertical-align:middle;">
                <div>Insurer</div>
                <img src="{{ public_path('insurance/'.$data->insurance_company_id.'/'.$data->company_stamp) }}"
                    style="width:120px; height:60px; object-fit:contain; margin-top:10px;" />
            </td>

            <td style="text-align:center; vertical-align:middle;">
                <div>Insured</div>
                <img src="{{ public_path('insurance/'.$data->insurance_company_id.'/'.$data->authorized_signature) }}"
                    style="width:120px; height:60px; object-fit:contain; margin-top:10px;" />
            </td>
        </tr> -->
        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>
    </div>

</html>