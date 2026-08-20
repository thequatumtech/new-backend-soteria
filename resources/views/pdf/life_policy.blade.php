<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Insurance Policy</title>
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

    .signatures>tbody>tr>td {
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

    .signature-box {
        width: 100%;
        height: 80px;
        text-align: center;
        margin-top: 8px;
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
        padding-bottom: 5px;
    }

    .insured-signature-table .insured-image {
        height: 80px;
        padding-bottom: 5px;
    }

    .insured-signature-table .authorized-image {
        height: 80px;
        padding-top: 5px;
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
                <td>{{ $data->plan_name }}</td>
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{$data->first_name}} {{$data->last_name}} {{$data->third_name}} {{ $data->family_name }}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{date('m-d-Y', strtotime($data->inception_date))}}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{date('m-d-Y', strtotime($data->expiry_date))}}</td>
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
                    @isset($plan->fees_amount)<td>Fees ({{ $plan->fees }}%)</td>@endisset
                    @isset($plan->stamps_amount)<td>Stamps ({{ $plan->stamps }}%)</td>@endisset
                    @isset($plan->sales_tax_amount)<td>Sales Tax ({{ $plan->sales_tax }}%)</td>@endisset
                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                    <td>Contribution to Guarantee Fund (CBJ) ({{ $plan->cbj }}%)</td>
                    @endif
                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                    <td>Sales Tax on CBJ Contribution Fund ({{ $plan->sales_tax_cbj }}%)</td>
                    @endif
                    @isset($plan->gross_premium_amount)<td>Gross Premium</td>@endisset
                </tr>

                <tr>
                    @isset($plan->limit)
                    <td>{{ number_format((float) $plan->limit, 2) }} {{ $abbr }}</td>
                    @endisset

                    @isset($plan->net_premium_amount)
                    <td>{{ number_format((float) $plan->net_premium_amount, 2) }} {{ $abbr }}</td>
                    @endisset

                    @isset($plan->fees_amount)
                    <td>{{ number_format((float) $plan->fees_amount, 2) }} {{ $abbr }}</td>
                    @endisset

                    @isset($plan->stamps_amount)
                    <td>{{ number_format((float) $plan->stamps_amount, 2) }} {{ $abbr }}</td>
                    @endisset

                    @isset($plan->sales_tax_amount)
                    <td>{{ number_format((float) $plan->sales_tax_amount, 2) }} {{ $abbr }}</td>
                    @endisset

                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                    <td>{{ number_format((float) $plan->cbj_amount, 2) }} {{ $abbr }}</td>
                    @endif

                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                    <td>{{ number_format((float) $plan->sales_tax_cbj_amount, 2) }} {{ $abbr }}</td>
                    @endif

                    @isset($plan->gross_premium_amount)
                    <td>{{ number_format((float) $plan->gross_premium_amount, 2) }} {{ $abbr }}</td>
                    @endisset
                </tr>
            </table>
        </div>

    </div>

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
                        <!-- @if(isset($cover->cover_limit_type) && $cover->cover_limit_type == 1)
                        {{ number_format((float)$cover->cover_limit, 2) }} {{ $abbr ?? 'JOD' }}
                        @else -->
                        {{ $cover->cover_limit }}
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
            <td>
                Insurer

                <div class="signature-box">
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
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td class="authorized-image">
                            <img
                                src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature)}}"
                                alt="Authorized Signature"
                                width="150"
                                height="70"
                                class="signature-image">
                        </td>
                    </tr>

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