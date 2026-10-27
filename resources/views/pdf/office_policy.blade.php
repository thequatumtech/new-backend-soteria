<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Insurance Policy</title>
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
            <h1>Office Insurance Policy</h1>
        </header>


{{-- APPLICATION DETAILS --}}
<table class="policy-details">
    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Applicant Details
        </td>
    </tr>
    <tr>
        <td>Full Name</td>
        <td>{{ trim(($data->first_name ?? '') . ' ' . ($data->last_name ?? '') . ' ' . ($data->third_name ?? '') . ' ' . ($data->family_name ?? '')) }}</td>
    </tr>
    <tr>
        <td>Nationality</td>
        <td>{{ $data->nationality ?? '' }}</td>
    </tr>
    <tr>
        <td>Nationality Number</td>
        <td>{{ $data->nationality_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Residence ID</td>
        <td>{{ $data->id_residence_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Date of Birth</td>
        <td>{{ !empty($data->birth_date) ? date('m-d-Y', strtotime($data->birth_date)) : '' }}</td>
    </tr>
    <tr>
        <td>Gender</td>
        <td>{{ $data->gender ?? '' }}</td>
    </tr>
    <tr>
        <td>Place of Residence</td>
        <td>{{ $data->place_residence ?? '' }}</td>
    </tr>

    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Office / Company Details
        </td>
    </tr>
    <tr>
        <td>Company Name</td>
        <td>{{ $data->company_name ?? '' }}</td>
    </tr>
    <tr>
        <td>Company Registration National ID</td>
        <td>{{ $data->company_register_national_id ?? '' }}</td>
    </tr>
    <tr>
        <td>Company Registration ID</td>
        <td>{{ $data->company_register_id ?? '' }}</td>
    </tr>
    <tr>
        <td>Office Type</td>
        <td>{{ $data->office_type ?? '' }}</td>
    </tr>
    <tr>
        <td>Office Category</td>
        <td>{{ $data->office_category ?? '' }}</td>
    </tr>
    <tr>
        <td>Number of Floors</td>
        <td>{{ $data->no_of_floor ?? '' }}</td>
    </tr>
    <tr>
        <td>Number of Rooms</td>
        <td>{{ $data->no_of_room ?? '' }}</td>
    </tr>
    <tr>
        <td>Apartment Size</td>
        <td>{{ $data->size_of_apartment ?? '' }}</td>
    </tr>
    <tr>
        <td>Age of Apartment</td>
        <td>{{ $data->age_of_apartment ?? '' }}</td>
    </tr>
    <tr>
        <td>Number of Residences</td>
        <td>{{ $data->no_of_residence ?? '' }}</td>
    </tr>
    <tr>
        <td>Number of Employees</td>
        <td>{{ $data->no_of_employee ?? '' }}</td>
    </tr>

    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Office Address
        </td>
    </tr>
    <tr>
        <td>Country</td>
        <td>{{ $data->country_name ?? $data->country_id ?? '' }}</td>
    </tr>
    <tr>
        <td>City</td>
        <td>{{ $data->city_name ?? $data->city_id ?? '' }}</td>
    </tr>
    <tr>
        <td>District</td>
        <td>{{ $data->district_name ?? $data->district_id ?? '' }}</td>
    </tr>
    <tr>
        <td>Street Name</td>
        <td>{{ $data->street_name ?? '' }}</td>
    </tr>
    <tr>
        <td>Building Number</td>
        <td>{{ $data->building_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Office Number</td>
        <td>{{ $data->office_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Block Number</td>
        <td>{{ $data->block_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Plate Number</td>
        <td>{{ $data->plate_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Plot Number</td>
        <td>{{ $data->plot_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Company Telephone</td>
        <td>{{ $data->company_telephone ?? '' }}</td>
    </tr>
    <tr>
        <td>Company Owner</td>
        <td>{{ $data->company_owner_name ?? '' }}</td>
    </tr>
    <tr>
        <td>Owner Telephone</td>
        <td>{{ $data->company_owner_telephone ?? '' }}</td>
    </tr>

    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Insurance Details
        </td>
    </tr>
    <tr>
        <td>Effective Date</td>
        <td>{{ !empty($data->effective_date) ? date('m-d-Y', strtotime($data->effective_date)) : '' }}</td>
    </tr>
    <tr>
        <td>Expiry Date</td>
        <td>{{ !empty($data->expiry_date) ? date('m-d-Y', strtotime($data->expiry_date)) : '' }}</td>
    </tr>
    <tr>
        <td>Insurance Limit</td>
        <td>{{ $data->insurance_limit ?? '' }}</td>
    </tr>
    <tr>
        <td>Protection System</td>
        <td>{{ $data->protection_system ?? '' }}</td>
    </tr>
    <tr>
        <td>Inception Date</td>
        <td>{{ !empty($data->inception_date) ? date('m-d-Y', strtotime($data->inception_date)) : '' }}</td>
    </tr>
    <tr>
        <td>Insurance Expiry Date</td>
        <td>{{ !empty($data->insurance_expiry_date) ? date('m-d-Y', strtotime($data->insurance_expiry_date)) : '' }}</td>
    </tr>

    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Declarations
        </td>
    </tr>
    <tr>
        <td>Partner Company Status</td>
        <td>{{ $data->partner_company_status ?? '' }}</td>
    </tr>
    <tr>
        <td>Authorized Insurance Policy Status</td>
        <td>{{ $data->authorized_insurance_police_status ?? '' }}</td>
    </tr>
    <tr>
        <td>Company Registration Status</td>
        <td>{{ $data->auth_company_register_status ?? '' }}</td>
    </tr>
    <tr>
        <td>Previous Insurance Policy</td>
        <td>{{ $data->provious_insurance_policy ?? '' }}</td>
    </tr>
    <tr>
        <td>Insurance Declined</td>
        <td>{{ $data->insurance_declined_issue_status ?? '' }}</td>
    </tr>
    <tr>
        <td>Claims in the Last 5 Years</td>
        <td>{{ $data->claims_5_year_status ?? '' }}</td>
    </tr>

    @if(!empty($data->renew))
        <tr>
            <td>Renewal</td>
            <td>Yes</td>
        </tr>
        <tr>
            <td>Previous Policy ID</td>
            <td>{{ $data->old_policy_id_for_renew ?? '' }}</td>
        </tr>
    @else
        <tr>
            <td>Renewal</td>
            <td>No</td>
        </tr>
    @endif

   
</table>


        <!-- POLICY DETAILS -->
        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>{{ $data->police_no }}</td>
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

        @php
        $columns = [];

        if (!empty($purchase->policy_plan_limit))
        $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;

        if (!empty($purchase->net_premium))
        $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $feesRate = $plan->fees ?? 0;
        $fees_label = 'Issuance Fees' . ($feesRate > 0 ? ' (' . $feesRate . '%)' : '');
        if (!empty($purchase->fees))
        $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stampsRate = $plan->stamps ?? 0;
        $stamps_label = 'Stamps' . ($stampsRate > 0 ? ' (' . $stampsRate . '%)' : '');
        if (!empty($purchase->stamps))
        $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $salesTaxRate = $plan->sales_tax ?? 0;
        $sales_tax_label = 'Sales Tax' . ($salesTaxRate > 0 ? ' (' . $salesTaxRate . '%)' : '');
        if (!empty($purchase->sales_tax))
        $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbjRate = $plan->cbj ?? 0;
        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($cbjRate > 0 ? ' (' . $cbjRate . '%)' : '');
        if (!empty($purchase->cbj))
        $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbjTaxRate = $plan->sales_tax_cbj ?? 0;
        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($cbjTaxRate > 0 ? ' (' . $cbjTaxRate . '%)' : '');
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
