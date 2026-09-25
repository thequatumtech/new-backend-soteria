<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marine Insurance Policy</title>
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

    /* Scrollable wrapper for wide tables on mobile */
    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* ========================= PREMIUM SUMMARY TABLE ========================== */
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

        .letterhead img {
            max-height: 80px;
        }

        .policy-header .logo {
            max-width: 120px;
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
            <h1>Marine Insurance Policy</h1>
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
                <td>{{ date('m-d-Y', strtotime($data->effective_date)) }}</td>
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

        $columns = [];

        if (!empty($purchase->policy_plan_limit))
        $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;

        if (!empty($purchase->net_premium))
        $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . ($data->fees > 0 ? ' (' . $data->fees . '%)' : '');
        if (!empty($purchase->fees))
        $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . ($data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');
        if (!empty($purchase->stamps))
        $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . ($data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');
        if (!empty($purchase->sales_tax))
        $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');
        if (!empty($purchase->cbj))
        $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($data->sales_tax_cbj > 0 ? ' (' . $data->sales_tax_cbj . '%)' : '');
        if (!empty($purchase->sales_tax_cbj))
        $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;

        if (!empty($purchase->gross_premium))
        $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;
        @endphp

        <!-- PREMIUM SUMMARY -->
        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <table class="premium-summary-table">
                @foreach($columns as $label => $value)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </table>
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