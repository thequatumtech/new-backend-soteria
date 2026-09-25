<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets Insurance Policy</title>
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

    /* Pet details label cells */
    .policy-details td.label {
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

    /* ========================= TABLE SCROLL ========================== */
    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ========================= SIGNATURE SECTION ========================== */
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

        .policy-header .logo {
            max-width: 120px;
        }

        .letterhead img {
            max-height: 80px;
        }

        /*
         * Pet details remain a 4-column label/value structure on desktop,
         * but become stacked label/value pairs on mobile.
         */
        .policy-details.four-col,
        .policy-details.four-col tbody,
        .policy-details.four-col tr {
            display: block;
            width: 100%;
        }

        .policy-details.four-col tr {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            margin-bottom: 8px;
        }

        .policy-details.four-col td {
            flex: 1 1 50%;
            border: none;
            border-bottom: 1px solid #e0e0e0;
            padding: 8px 10px;
        }

        .policy-details.four-col td[colspan] {
            flex: 1 1 100%;
        }

        .policy-details.four-col td.label {
            background-color: #e0e0e0;
            font-weight: bold;
            white-space: normal;
        }

        /* Policy Covers — preserve table layout */
        .table-scroll {
            margin-bottom: 4px;
        }

        .table-scroll .policy-details {
            display: table !important;
            width: 100%;
        }

        .table-scroll .policy-details tbody {
            display: table-row-group;
        }

        .table-scroll .policy-details tr {
            display: table-row;
            width: auto;
            margin-bottom: 0;
            border: none;
        }

        .table-scroll .policy-details td {
            display: table-cell;
            width: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .table-scroll .policy-details td:first-child,
        .table-scroll .policy-details td.label {
            background-color: #e0e0e0;
            font-weight: bold;
            white-space: nowrap;
        }

        /* Premium summary */
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

        /* Signatures — stack vertically */
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

        footer {
            font-size: 11px;
            padding: 0 5px;
        }
    }
</style>

<body>
    <div class="policy-container">

        {{-- =====================================================
     LETTERHEAD — show only if letterhead image exists
====================================================== --}}
        @php
        $letterHeadPath = null;
        if (!empty($data->letterhead)) {
        $possibleLetterHeadPath = public_path('insurance/' . $data->insurance_company_id . '/' . $data->letterhead);
        if (file_exists($possibleLetterHeadPath)) {
        $letterHeadPath = $possibleLetterHeadPath;
        }
        }
        @endphp

        @if($letterHeadPath)
        <div class="letterhead">
            <img src="{{ $letterHeadPath }}" alt="{{ $data->company_name ?? 'Insurance Company' }} Letterhead">
        </div>
        @endif

        <header class="policy-header">
            <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo) }}"
                alt="{{ $data->company_name }}" class="logo">
            <h1>Pets Insurance Policy</h1>
        </header>

        <!-- POLICY DETAILS — 4-column layout, stacks to 2-col pairs on mobile -->
        <table class="policy-details four-col">
            <tr>
                <td class="label"><strong>Policy No.</strong></td>
                <td>({{ $data->police_no }})</td>
                <td class="label"><strong>Plan Name</strong></td>
                <td>{{ $data->plan_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Policy Holder</strong></td>
                <td colspan="3">{{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Effective Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
                <td class="label"><strong>Expiry Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Pets Name</strong></td>
                <td>{{ $data->pets_name }}</td>
                <td class="label"><strong>Birth Date</strong></td>
                <td>{{ $data->pets_dob }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Pets Type</strong></td>
                <td>{{ $data->pets_type == 1 ? 'Dog' : 'Cat' }}</td>
                <td class="label"><strong>Nationality No</strong></td>
                <td>{{ $data->nationality_no }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Gender</strong></td>
                <td>{{ $data->gender }}</td>
                <td class="label"><strong>Breed</strong></td>
                <td>{{ $data->breed }}</td>
            </tr>
            <tr>
                <td class="label"><strong>Plan Name</strong></td>
                <td>{{ $data->plan_name }}</td>
                <td class="label"><strong>Insurance Company</strong></td>
                <td>{{ $data->company_name }}</td>
            </tr>
        </table>

        @php
        $abbr = $abbr ?? 'JOD';

        /*
        |--------------------------------------------------------------------------
        | PREMIUM SUMMARY
        | Same design structure as Home Insurance Premium Summary
        |--------------------------------------------------------------------------
        */
        $columns = [];

        if (!empty($purchase->policy_plan_limit))
        $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;

        if (!empty($purchase->net_premium))
        $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . (!empty($data->fees) && $data->fees > 0 ? ' (' . $data->fees . '%)' : '');
        if (!empty($purchase->fees))
        $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . (!empty($data->stamps) && $data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');
        if (!empty($purchase->stamps))
        $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . (!empty($data->sales_tax) && $data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');
        if (!empty($purchase->sales_tax))
        $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . (!empty($data->cbj) && $data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');
        if (!empty($purchase->cbj))
        $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . (!empty($data->sales_tax_cbj) && $data->sales_tax_cbj > 0 ? ' (' . $data->sales_tax_cbj . '%)' : '');
        if (!empty($purchase->sales_tax_cbj))
        $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;

        if (!empty($purchase->gross_premium))
        $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;
        @endphp

        <!-- PREMIUM SUMMARY -->
        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>

            <div class="table-scroll">
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

        <!-- POLICY COVERS -->
        @if(!empty($plan->policy_covers) && count($plan->policy_covers) > 0)
        <div class="policy-summary">
            <h4>Policy Covers</h4>
            <div class="table-scroll">
                <table class="policy-details" style="min-width: 400px;">
                    <tr>
                        <td style="background-color: #e0e0e0;"><strong>Name of Cover</strong></td>
                        <td style="background-color: #e0e0e0;"><strong>Limit</strong></td>
                        <td style="background-color: #e0e0e0;"><strong>Deductible</strong></td>
                        <td style="background-color: #e0e0e0;"><strong>Rate</strong></td>
                        <td style="background-color: #e0e0e0;"><strong>Premium</strong></td>
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
        </div>
        @endif

        <!-- POLICY TEXT -->
        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        @php
        /*
        |--------------------------------------------------------------------------
        | Check User Signature
        |--------------------------------------------------------------------------
        */
        $userSignaturePath = null;

        if (!empty($data->user_signature)) {
        $path = public_path($data->user_signature);

        if (file_exists($path)) {
        $userSignaturePath = $path;
        }
        }

        /*
        |--------------------------------------------------------------------------
        | Company Stamp
        |--------------------------------------------------------------------------
        */
        $companyStampPath = null;

        if (!empty($data->company_stamp)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp);

        if (file_exists($path)) {
        $companyStampPath = $path;
        }
        }

        /*
        |--------------------------------------------------------------------------
        | Authorized Signature
        |--------------------------------------------------------------------------
        */
        $authorizedSignaturePath = null;

        if (!empty($data->authorized_signature)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature);

        if (file_exists($path)) {
        $authorizedSignaturePath = $path;
        }
        }
        @endphp

        @if($userSignaturePath)

        {{-- =========================================================
         USER SIGNATURE AVAILABLE — 3 COLUMNS
         Insurer | Insured | Authorized Signature
    ========================================================== --}}
        <table class="signatures three-columns">
            <tr>

                {{-- INSURER --}}
                <td>
                    <div class="signature-title">Insurer</div>
                    <div>{{ $data->company_name }}</div>

                    @if($companyStampPath)
                    <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                    @endif
                </td>

                {{-- INSURED --}}
                <td>
                    <div class="signature-title">Insured</div>

                    <div>
                        {{ $data->first_name }}
                        {{ $data->last_name }}
                        {{ $data->third_name }}
                        {{ $data->family_name }}
                    </div>

                    <img src="{{ $userSignaturePath }}" alt="User Signature" class="signature-image">
                </td>

                {{-- AUTHORIZED SIGNATURE --}}
                <td>
                    <div class="signature-title">Authorized Signature</div>

                    @if($authorizedSignaturePath)
                    <img src="{{ $authorizedSignaturePath }}" alt="Authorized Signature" class="signature-image">
                    @endif
                </td>

            </tr>
        </table>

        @else

        {{-- =========================================================
         USER SIGNATURE NOT AVAILABLE — 2 COLUMNS
         Insurer | Authorized Signature
    ========================================================== --}}
        <table class="signatures">
            <tr>

                {{-- INSURER --}}
                <td>
                    <div class="signature-title">Insurer</div>
                    <div>{{ $data->company_name }}</div>

                    @if($companyStampPath)
                    <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                    @endif
                </td>

                {{-- AUTHORIZED SIGNATURE --}}
                <td>
                    <div class="signature-title">Authorized Signature</div>

                    @if($authorizedSignaturePath)
                    <img src="{{ $authorizedSignaturePath }}" alt="Authorized Signature" class="signature-image">
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