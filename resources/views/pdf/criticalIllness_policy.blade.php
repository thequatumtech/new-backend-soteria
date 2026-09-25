<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Critical Illness Insurance Policy</title>
    <link rel="stylesheet" href="styles.css">
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

    /* ========================= PREMIUM SUMMARY ========================== */
    .policy-summary {
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .policy-summary h4 {
        margin-bottom: 8px;
    }

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

    /* ========================= TABLE RESPONSIVE ========================== */
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

        /* Premium summary & policy covers — horizontal scroll */
        .table-responsive {
            margin-bottom: 4px;
        }

        /*
         * Keep Policy Covers table in normal table layout
         * while top Policy Details remains stacked.
         */
        .table-responsive .policy-details {
            display: table;
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
            <h1>Critical Illness insurance Policy</h1>
        </header>

        <div class="table-responsive">
            <table class="policy-details stack-on-mobile">
                <tr>
                    <td><strong>Policy No.</strong></td>
                    <td>{{$data->police_no}}</td>
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
        $columns = [];
        $abbr = $abbr ?? 'JOD';

        $feesRate = $plan->fees ?? 0;
        $stampsRate = $plan->stamps ?? 0;
        $salesTaxRate = $plan->sales_tax ?? 0;
        $cbjRate = $plan->cbj ?? 0;
        $cbjTaxRate = $plan->sales_tax_cbj ?? 0;

        if (!empty($purchase->policy_plan_limit))
        $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;

        if (!empty($purchase->net_premium))
        $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . ($feesRate > 0 ? ' (' . $feesRate . '%)' : '');
        if (!empty($purchase->fees))
        $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . ($stampsRate > 0 ? ' (' . $stampsRate . '%)' : '');
        if (!empty($purchase->stamps))
        $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . ($salesTaxRate > 0 ? ' (' . $salesTaxRate . '%)' : '');
        if (!empty($purchase->sales_tax))
        $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($cbjRate > 0 ? ' (' . $cbjRate . '%)' : '');
        if (!empty($purchase->cbj))
        $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($cbjTaxRate > 0 ? ' (' . $cbjTaxRate . '%)' : '');
        if (!empty($purchase->sales_tax_cbj))
        $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;

        if (!empty($purchase->gross_premium))
        $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;
        @endphp

        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <div class="table-responsive">
                <table class="premium-summary-table">
                    @foreach($columns as $label => $value)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        <div class="policy-summary">
            <h4>Policy Covers</h4>

            <div class="table-responsive">
                <table class="policy-details">
                    <tr>
                        <td><strong>Name of Cover</strong></td>
                        <td><strong>Limit</strong></td>
                        <td><strong>Deductible</strong></td>
                        <td><strong>Premium</strong></td>
                    </tr>

                    @foreach($plan->policy_covers as $cover)
                    <tr>
                        <td>{{ $cover->cover_name }}</td>
                        <td>{{ number_format($cover->cover_limit, 2) }}</td>
                        <td>{{ number_format($cover->cover_deductible, 2) }}</td>
                        <td>{{ number_format($cover->cover_premium, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        @php
        /*
        Check User Signature
        --------------------------------------------------------------------------
        */
        $userSignaturePath = null;

        if (!empty($data->user_signature)) {
        $path = public_path($data->user_signature);

        if (file_exists($path)) {
        $userSignaturePath = $path;
        }

        }

        /*
        Company Stamp
        --------------------------------------------------------------------------
        */
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

        /*
        Authorized Signature
        --------------------------------------------------------------------------
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
