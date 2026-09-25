<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Medical Insurance Policy</title>
    {{-- <link rel="stylesheet" href="styles.css"> --}}
</head>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #ffffff;
    }

    .policy-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border: 1px solid #ddd;
    }

    /* ========================= LETTERHEAD ========================== */
    .letterhead {
        width: 100%;
        margin-bottom: 20px;
        text-align: center;
    }

    .letterhead img {
        width: 100%;
        height: auto;
        max-height: 120px;
        object-fit: contain;
        display: block;
    }

    .policy-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .policy-header .logo {
        max-width: 150px;
        height: auto;
        margin-bottom: 10px;
    }

    .policy-header h1 {
        font-size: 18px;
        margin: 0;
        font-weight: normal;
    }

    /* ========================= POLICY DETAILS TABLE ========================== */
    .policy-details {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .policy-details td {
        padding: 10px;
        border: 1px solid #ccc;
        word-break: break-word;
    }

    .policy-details td:first-child {
        font-weight: bold;
        background-color: #e0e0e0;
        white-space: nowrap;
    }

    /* ========================= POLICY SUMMARY ========================== */
    .policy-summary {
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .policy-summary h4 {
        margin-bottom: 8px;
    }

    .policy-summary table.policy-details {
        table-layout: auto;
    }

    /* ========================= PREMIUM SUMMARY ========================== */
    .premium-summary-table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 14px;
    }

    .premium-summary-table td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        vertical-align: middle;
    }

    .premium-summary-table td:first-child {
        width: 55%;
        font-weight: bold;
        background-color: #e0e0e0;
    }

    .premium-summary-table td:last-child {
        width: 45%;
    }

    /* Scrollable wrapper for wide tables on mobile */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ========================= SIGNATURES ========================== */
    .signatures {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .signatures td {
        padding: 15px;
        border: 1px solid #ccc;
        vertical-align: top;
        text-align: center;
        width: 50%;
    }

    .signatures.three-columns td {
        width: 33.33%;
    }

    .signature-title {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .signature-image {
        display: block;
        max-width: 150px;
        max-height: 80px;
        width: auto;
        height: auto;
        object-fit: contain;
        margin: 10px auto 0;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #666;
    }

    /* ========================= MOBILE BREAKPOINT ========================== */
    @media (max-width: 600px) {
        .policy-container {
            margin: 0;
            padding: 12px;
            border: none;
        }

        .policy-header h1 {
            font-size: 16px;
        }

        /* Stack two-column detail rows into label/value pairs */
        .policy-details,
        .policy-details tbody,
        .policy-details tr,
        .policy-details td {
            display: block;
            width: 100%;
        }

        .policy-details tr {
            margin-bottom: 8px;
            border: 1px solid #ccc;
        }

        .policy-details td {
            border: none;
            border-bottom: 1px solid #e0e0e0;
            padding: 8px 10px;
        }

        .policy-details td:last-child {
            border-bottom: none;
        }

        .policy-details td:first-child {
            background-color: #e0e0e0;
            font-weight: bold;
            white-space: normal;
        }

        .premium-summary-table {
            font-size: 12px;
        }

        .premium-summary-table td {
            padding: 5px 6px;
        }

        .premium-summary-table td:first-child {
            width: 55%;
        }

        .premium-summary-table td:last-child {
            width: 45%;
        }

        /* Policy Covers / Additional Benefits keep table layout */
        .table-responsive {
            margin-bottom: 4px;
        }

        .table-responsive .policy-details {
            display: table !important;
            width: 100%;
        }

        .table-responsive .policy-details tbody {
            display: table-row-group;
        }

        .table-responsive .policy-details tr {
            display: table-row;
            width: auto;
            margin-bottom: 0;
            border: none;
        }

        .table-responsive .policy-details td {
            display: table-cell;
            width: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .table-responsive .policy-details td:first-child {
            background-color: #e0e0e0;
            font-weight: bold;
            white-space: nowrap;
        }

        /* Signatures — stack vertically on small screens */
        .signatures,
        .signatures.three-columns {
            display: block;
        }

        .signatures tr {
            display: flex;
            flex-direction: column;
        }

        .signatures td,
        .signatures.three-columns td {
            width: 100%;
            border-bottom: 1px solid #ccc;
        }

        .signatures td:last-child {
            border-bottom: none;
        }

        .signature-image {
            max-width: 120px;
        }

        .letterhead img {
            max-height: 80px;
        }

        .policy-header .logo {
            max-width: 120px;
        }
    }
</style>

<body>
    <div class="policy-container">


        {{-- ===================================================== LETTERHEAD Show only if letterhead exists
    ====================================================== --}} @php $letterHeadPath = null;


        if (!empty($data->letterhead)) {
        $possibleLetterHeadPath = public_path('insurance/' . $data->insurance_company_id . '/' . $data->letterhead);
        if (file_exists($possibleLetterHeadPath)) {
        $letterHeadPath = $possibleLetterHeadPath;
        }
        } @endphp


        @if($letterHeadPath)
        <div class="letterhead"> <img src="{{ $letterHeadPath }}"
                alt="{{ $data->company_name ?? 'Insurance Company' }} Letterhead"> </div> @endif

        <header class="policy-header">
            <img src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo)}}" alt="{{ $data->company_name }}" class="logo">
            <h1>Family Medical insurance Policy</h1>
        </header>

        <div class="table-responsive">
            <table class="policy-details stack-on-mobile">
                <tr>
                    <td><strong>Policy No.</strong></td>
                    <td>({{$data->police_no}})</td>
                </tr>
                <tr>
                    <td><strong>Plan Name</strong></td>
                    <td>{{$data->plan_name}}</td>
                </tr>
                <tr>
                    <td><strong>Policy Holder</strong></td>
                    <td>{{$data->first_name}} {{$data->last_name}} {{$data->third_name}} {{$data->family_name}}</td>
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
        </div>

        @php
        $abbr = $abbr ?? 'JOD';
        $summaryColumns = [];

        if (isset($plan->limit))
        $summaryColumns['Limit'] = number_format((float) $plan->limit, 2) . ' ' . $abbr;

        if (isset($plan->net_premium_amount))
        $summaryColumns['Net Premium'] = number_format((float) $plan->net_premium_amount, 2) . ' ' . $abbr;

        if (isset($plan->fees) && isset($plan->fees_amount))
        $summaryColumns['Fees (' . $plan->fees . '%)'] = number_format((float) $plan->fees_amount, 2) . ' ' . $abbr;

        if (isset($plan->stamps) && isset($plan->stamps_amount))
        $summaryColumns['Stamps (' . $plan->stamps . '%)'] = number_format((float) $plan->stamps_amount, 2) . ' ' . $abbr;

        if (isset($plan->sales_tax) && isset($plan->sales_tax_amount))
        $summaryColumns['Sales Tax (' . $plan->sales_tax . '%)'] = number_format((float) $plan->sales_tax_amount, 2) . ' ' . $abbr;

        if (isset($plan->cbj_amount) && $plan->cbj_amount > 0)
        $summaryColumns['Contribution to Guarantee Fund (CBJ) (' . $plan->cbj . '%)'] = number_format((float) $plan->cbj_amount, 2) . ' ' . $abbr;

        if (isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
        $summaryColumns['Sales Tax on CBJ Contribution Fund (' . $plan->sales_tax_cbj . '%)'] = number_format((float) $plan->sales_tax_cbj_amount, 2) . ' ' . $abbr;

        if (isset($plan->gross_premium_amount))
        $summaryColumns['Gross Premium'] = number_format((float) $plan->gross_premium_amount, 2) . ' ' . $abbr;
        @endphp

        @if(count($summaryColumns) > 0)
        <div class="policy-summary">
            <h4>Policy Summary</h4>

            <div class="table-responsive">
                {{-- <table class="policy-details">
                <tr>
                    @isset($plan->limit)<td>Limit</td>@endisset
                    @isset($plan->net_premium_amount)<td>Net Premium</td>@endisset
                    @isset($plan->fees)<td>Fees ({{ $plan->fees }}%)</td>@endisset
                @isset($plan->stamps)<td>Stamps ({{ $plan->stamps }}%)</td>@endisset
                @isset($plan->sales_tax)<td>Sales Tax ({{ $plan->sales_tax }}%)</td>@endisset
                @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)<td>Contribution to Guarantee Fund (CBJ) ({{ $plan->cbj }}%)</td>@endif
                @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)<td>Sales Tax on CBJ Contribution Fund ({{ $plan->sales_tax_cbj }}%)</td>@endif
                @isset($plan->gross_premium_amount)<td>Gross Premium</td>@endisset
                </tr>

                <tr>
                    @isset($plan->limit)<td>{{ number_format((float) $plan->limit, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->net_premium_amount)<td>{{ number_format((float) $plan->net_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->fees_amount)<td>{{ number_format((float) $plan->fees_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->stamps_amount)<td>{{ number_format((float) $plan->stamps_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @isset($plan->sales_tax_amount)<td>{{ number_format((float) $plan->sales_tax_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)<td>{{ number_format((float) $plan->cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endif
                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)<td>{{ number_format((float) $plan->sales_tax_cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endif
                    @isset($plan->gross_premium_amount)<td>{{ number_format((float) $plan->gross_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>@endisset
                </tr>
                </table> --}}
                <table class="premium-summary-table">
                    @foreach($summaryColumns as $label => $value)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        @if(!empty($plan->policy_covers) && count($plan->policy_covers) > 0)

        <h4>Policy Covers</h4>

        <div class="table-responsive">
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
        </div>

        @endif
        @if(!empty($plan->additional_benefits) && count($plan->additional_benefits) > 0)

        <h4>Additional Benefits</h4>

        <div class="table-responsive">
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
        </div>

        @endif

        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        @php


        /* |
        | -------------------------------------------------------------------------- |
        | Check User Signature |
        | -------------------------------------------------------------------------- |
        | */
        $userSignaturePath = null;

        if (!empty($data->user_signature)) {
        $path = public_path($data->user_signature);


        if (file_exists($path)) {
        $userSignaturePath = $path;
        }


        }

        /* |
        | -------------------------------------------------------------------------- |
        | Company Stamp |
        | -------------------------------------------------------------------------- |
        | */
        $companyStampPath = null;

        if (!empty($data->company_stamp)) {
        $path = public_path(
        'insurance/' .
        $data->insurance_company_id .
        '/' .
        $data->company_stamp
        );


        if (file_exists($path)) {
        $companyStampPath = $path;
        }


        }

        /* |
        | -------------------------------------------------------------------------- |
        | Authorized Signature |
        | -------------------------------------------------------------------------- |
        */
        $authorizedSignaturePath = null;

        if (!empty($data->authorized_signature)) {
        $path = public_path(
        'insurance/' .
        $data->insurance_company_id .
        '/' .
        $data->authorized_signature
        );


        if (file_exists($path)) {
        $authorizedSignaturePath = $path;
        }


        }
        @endphp

        @if($userSignaturePath)


        {{-- =========================================================
     USER SIGNATURE AVAILABLE
     3 COLUMNS

     Insurer | Insured | Authorized Signature
========================================================== --}}

        <table class="signatures three-columns">

            <tr>

                {{-- INSURER --}}
                <td>

                    <div class="signature-title">
                        Insurer
                    </div>

                    <div>
                        {{ $data->company_name }}
                    </div>

                    @if($companyStampPath)
                    <img
                        src="{{ $companyStampPath }}"
                        alt="Company Stamp"
                        class="signature-image">
                    @endif

                </td>


                {{-- INSURED --}}
                <td>

                    <div class="signature-title">
                        Insured
                    </div>

                    <div>
                        {{ $data->first_name }}
                        {{ $data->last_name }}
                        {{ $data->third_name }}
                        {{ $data->family_name }}
                    </div>

                    <img
                        src="{{ $userSignaturePath }}"
                        alt="User Signature"
                        class="signature-image">

                </td>


                {{-- AUTHORIZED SIGNATURE --}}
                <td>

                    <div class="signature-title">
                        Authorized Signature
                    </div>

                    @if($authorizedSignaturePath)
                    <img
                        src="{{ $authorizedSignaturePath }}"
                        alt="Authorized Signature"
                        class="signature-image">
                    @endif

                </td>

            </tr>

        </table>


        @else


        {{-- =========================================================
     USER SIGNATURE NOT AVAILABLE
     2 COLUMNS

     Insurer | Authorized Signature
========================================================== --}}

        <table class="signatures">

            <tr>

                {{-- INSURER --}}
                <td>

                    <div class="signature-title">
                        Insurer
                    </div>

                    <div>
                        {{ $data->company_name }}
                    </div>

                    @if($companyStampPath)
                    <img
                        src="{{ $companyStampPath }}"
                        alt="Company Stamp"
                        class="signature-image">
                    @endif

                </td>


                {{-- AUTHORIZED SIGNATURE --}}
                <td>

                    <div class="signature-title">
                        Authorized Signature
                    </div>

                    @if($authorizedSignaturePath)
                    <img
                        src="{{ $authorizedSignaturePath }}"
                        alt="Authorized Signature"
                        class="signature-image">
                    @endif

                </td>
            </tr>
        </table>
        @endif

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>
    </div>
</body>

</html>