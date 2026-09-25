<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Insurance Policy</title>
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

        /* Policy Covers — preserve table layout */
        .table-scroll {
            margin-bottom: 4px;
        }

        .table-scroll .policy-details,
        .table-scroll .policy-details tbody,
        .table-scroll .policy-details tr,
        .table-scroll .policy-details td {
            display: revert;
        }

        .table-scroll .policy-details {
            display: table !important;
            width: 100%;
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

        .footer {
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
            <h1>Motor Insurance Policy</h1>
        </header>

        <!-- POLICY DETAILS -->
        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>({{ $data->police_no }})</td>
            </tr>
            <tr>
                <td><strong>Plan Name</strong></td>
                <td>{{ $data->plan_name }}</td>
            </tr>
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</td>
            </tr>
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
            </tr>
            <tr>
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr>
        </table>

        {{-- ========================= PREMIUM SUMMARY (vertical label|value — matches Personal Accident) ========================= --}}
        @php
        $abbr = $abbr ?? 'JOD';
        $summaryColumns = [];

        if (isset($plan->limit))
        $summaryColumns['Limit'] = number_format((float) $plan->limit, 2) . ' ' . $abbr;

        if (isset($plan->net_premium_amount))
        $summaryColumns['Net Premium'] = number_format((float) $plan->net_premium_amount, 2) . ' ' . $abbr;

        if (isset($plan->fees->fees))
        $summaryColumns['Fees (' . $plan->fees->fees . '%)'] = number_format((float) ($plan->fees_amount ?? 0), 2) . ' ' . $abbr;

        if (isset($plan->fees->stamps))
        $summaryColumns['Stamps (' . $plan->fees->stamps . '%)'] = number_format((float) ($plan->stamps_amount ?? 0), 2) . ' ' . $abbr;

        if (isset($plan->fees->sales_tax))
        $summaryColumns['Sales Tax (' . $plan->fees->sales_tax . '%)'] = number_format((float) ($plan->sales_tax_amount ?? 0), 2) . ' ' . $abbr;

        if (isset($plan->fees->cbj) && $plan->fees->cbj > 0)
        $summaryColumns['Contribution to Guarantee Fund (CBJ) (' . $plan->fees->cbj . '%)'] = number_format((float) ($plan->cbj_amount ?? 0), 2) . ' ' . $abbr;

        if (isset($plan->fees->sales_tax_cbj) && $plan->fees->sales_tax_cbj > 0)
        $summaryColumns['Sales Tax on CBJ Contribution Fund (' . $plan->fees->sales_tax_cbj . '%)'] = number_format((float) ($plan->sales_tax_cbj_amount ?? 0), 2) . ' ' . $abbr;

        if (isset($plan->gross_premium_amount))
        $summaryColumns['Gross Premium'] = number_format((float) $plan->gross_premium_amount, 2) . ' ' . $abbr;
        @endphp

        <!-- PREMIUM SUMMARY -->
        @if(count($summaryColumns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <table class="premium-summary-table">
                @foreach($summaryColumns as $label => $value)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <!-- POLICY COVERS -->
        @if(isset($plan->policy_covers) && $plan->policy_covers->count() > 0)
        <div class="policy-summary">
            <h4>Policy Covers</h4>
            <div class="table-scroll">
                <table class="policy-details" style="min-width: 320px;">
                    <thead>
                        <tr>
                            <td style="background-color: #e0e0e0;"><strong>Name of Cover</strong></td>
                            <td style="background-color: #e0e0e0;"><strong>Limit</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plan->policy_covers as $cover)
                        <tr>
                            <td>{{ $cover->cover_name }}</td>
                            <td>{{ $cover->cover_limit }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ADDITIONAL BENEFITS -->
        @if(isset($plan->additional_benefits) && $plan->additional_benefits->count() > 0)
        <div class="policy-summary">
            <h4>Additional Benefits</h4>
            <div class="table-scroll">
                <table class="policy-details" style="min-width: 360px;">
                    <thead>
                        <tr>
                            <td style="background-color: #e0e0e0;"><strong>Name of Benefit</strong></td>
                            <td style="background-color: #e0e0e0;"><strong>Limit</strong></td>
                            <td style="background-color: #e0e0e0;"><strong>Deductible</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plan->additional_benefits as $benefit)
                        <tr>
                            <td>{{ $benefit->benefit_name }}</td>
                            <td>{{ $benefit->benefit_limit }}</td>
                            <td>{{ $benefit->benefit_deductible }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- POLICY TEXT -->
        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        @php
        $userSignaturePath = null;
        if (!empty($data->user_signature)) {
        $path = public_path($data->user_signature);
        if (file_exists($path)) { $userSignaturePath = $path; }
        }

        $companyStampPath = null;
        if (!empty($data->company_stamp)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp);
        if (file_exists($path)) { $companyStampPath = $path; }
        }

        $authorizedSignaturePath = null;
        if (!empty($data->authorized_signature)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature);
        if (file_exists($path)) { $authorizedSignaturePath = $path; }
        }
        @endphp

        @if($userSignaturePath)
        {{-- 3 COLUMNS: Insurer | Insured | Authorized Signature --}}
        <table class="signatures three-columns">
            <tr>
                <td>
                    <div class="signature-title">Insurer</div>
                    <div>{{ $data->company_name }}</div>
                    @if($companyStampPath)
                    <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                    @endif
                </td>
                <td>
                    <div class="signature-title">Insured</div>
                    <div>{{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</div>
                    <img src="{{ $userSignaturePath }}" alt="User Signature" class="signature-image">
                </td>
                <td>
                    <div class="signature-title">Authorized Signature</div>
                    @if($authorizedSignaturePath)
                    <img src="{{ $authorizedSignaturePath }}" alt="Authorized Signature" class="signature-image">
                    @endif
                </td>
            </tr>
        </table>

        @else
        {{-- 2 COLUMNS: Insurer | Authorized Signature --}}
        <table class="signatures">
            <tr>
                <td>
                    <div class="signature-title">Insurer</div>
                    <div>{{ $data->company_name }}</div>
                    @if($companyStampPath)
                    <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                    @endif
                </td>
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