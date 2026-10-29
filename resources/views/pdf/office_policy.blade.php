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

    @if(isset($isFinalPdf) && $isFinalPdf && !empty($letterHeadPath))
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
    @php
$fullName = trim(($data->first_name ?? '') . ' ' . ($data->last_name ?? '') . ' ' . ($data->third_name ?? '') . ' ' . ($data->family_name ?? ''));
    @endphp
    @if(!empty($fullName) || !empty($data->nationality) || !empty($data->nationality_no) || !empty($data->id_residence_no) || !empty($data->birth_date) || !empty($data->gender) || !empty($data->place_residence))
    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Applicant Details
        </td>
    </tr>
    @if(!empty($fullName))
    <tr>
        <td>Full Name</td>
        <td>{{ $fullName }}</td>
    </tr>
    @endif
    @if(!empty($data->nationality))
    <tr>
        <td>Nationality</td>
        <td>{{ $data->nationality }}</td>
    </tr>
    @endif
    @if(!empty($data->nationality_no))
    <tr>
        <td>Nationality Number</td>
        <td>{{ $data->nationality_no }}</td>
    </tr>
    @endif
    @if(!empty($data->id_residence_no))
    <tr>
        <td>Residence ID</td>
        <td>{{ $data->id_residence_no }}</td>
    </tr>
    @endif
    @if(!empty($data->birth_date))
    <tr>
        <td>Date of Birth</td>
        <td>{{ date('m-d-Y', strtotime($data->birth_date)) }}</td>
    </tr>
    @endif
    @if(!empty($data->gender))
    <tr>
        <td>Gender</td>
        <td>{{ $data->gender }}</td>
    </tr>
    @endif
    @if(!empty($data->place_residence))
    <tr>
        <td>Place of Residence</td>
        <td>{{ $data->place_residence }}</td>
    </tr>
    @endif
    @endif

    @if(!empty($data->company_name) || !empty($data->company_register_national_id) || !empty($data->company_register_id) || !empty($data->office_type) || !empty($data->office_category) || !empty($data->no_of_floor) || !empty($data->no_of_room) || !empty($data->size_of_apartment) || !empty($data->age_of_apartment) || !empty($data->no_of_residence) || !empty($data->no_of_employee))
        <tr>
            <td colspan="2" style="background-color: #d0d0d0;">
                Office / Company Details
            </td>
        </tr>
        @if(!empty($data->company_name))
            <tr>
                <td>Company Name</td>
                <td>{{ $data->office_company_name ?? '-' }}</td>
            </tr>
        @endif
        @if(!empty($data->company_register_national_id))
        <tr>
            <td>Company Registration National ID</td>
            <td>{{ $data->company_register_national_id }}</td>
        </tr>
        @endif
        @if(!empty($data->company_register_id))
        <tr>
            <td>Company Registration ID</td>
            <td>{{ $data->company_register_id }}</td>
        </tr>
        @endif
        @if(!empty($data->office_type))
        <tr>
            <td>Office Type</td>
            <td>{{ $data->office_type }}</td>
        </tr>
        @endif
        @if(!empty($data->office_category))
        <tr>
            <td>Office Category</td>
            <td>{{ $data->office_category }}</td>
        </tr>
        @endif
        @if(!empty($data->no_of_floor))
        <tr>
            <td>Number of Floors</td>
            <td>{{ $data->no_of_floor }}</td>
        </tr>
        @endif
        @if(!empty($data->no_of_room))
        <tr>
            <td>Number of Rooms</td>
            <td>{{ $data->no_of_room }}</td>
        </tr>
        @endif
        @if(!empty($data->size_of_apartment))
        <tr>
            <td>Apartment Size</td>
            <td>{{ $data->size_of_apartment }}</td>
        </tr>
        @endif
        @if(!empty($data->age_of_apartment))
        <tr>
            <td>Age of Apartment</td>
            <td>{{ $data->age_of_apartment }}</td>
        </tr>
        @endif
        @if(!empty($data->no_of_residence))
        <tr>
            <td>Number of Residences</td>
            <td>{{ $data->no_of_residence }}</td>
        </tr>
        @endif
        @if(!empty($data->no_of_employee))
        <tr>
            <td>Number of Employees</td>
            <td>{{ $data->no_of_employee }}</td>
        </tr>
        @endif
    @endif

    @php
$officeCountry = $data->country_name ?? (!empty($data->country_id) ? getCountryName($data->country_id) : null);
$officeCity = $data->city_name ?? (!empty($data->city_id) ? getCityName($data->city_id) : null);
$officeDistrict = $data->district_name ?? (!empty($data->district_id) ? getDistrictName($data->district_id) : null);
    @endphp
    @if(!empty($officeCountry) || !empty($officeCity) || !empty($officeDistrict) || !empty($data->street_name) || !empty($data->building_no) || !empty($data->office_no) || !empty($data->block_no) || !empty($data->plate_no) || !empty($data->plot_no) || !empty($data->company_telephone) || !empty($data->company_owner_name) || !empty($data->company_owner_telephone))
    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Office Address
        </td>
    </tr>
    @if(!empty($officeCountry) && $officeCountry !== '-')
    <tr>
        <td>Country</td>
        <td>{{ $officeCountry }}</td>
    </tr>
    @endif
    @if(!empty($officeCity) && $officeCity !== '-')
    <tr>
        <td>City</td>
        <td>{{ $officeCity }}</td>
    </tr>
    @endif
    @if(!empty($officeDistrict) && $officeDistrict !== '-')
    <tr>
        <td>District</td>
        <td>{{ $officeDistrict }}</td>
    </tr>
    @endif
    @if(!empty($data->street_name))
    <tr>
        <td>Street Name</td>
        <td>{{ $data->street_name }}</td>
    </tr>
    @endif
    @if(!empty($data->building_no))
    <tr>
        <td>Building Number</td>
        <td>{{ $data->building_no }}</td>
    </tr>
    @endif
    @if(!empty($data->office_no))
    <tr>
        <td>Office Number</td>
        <td>{{ $data->office_no }}</td>
    </tr>
    @endif
    @if(!empty($data->block_no))
    <tr>
        <td>Block Number</td>
        <td>{{ $data->block_no }}</td>
    </tr>
    @endif
    @if(!empty($data->plate_no))
    <tr>
        <td>Plate Number</td>
        <td>{{ $data->plate_no }}</td>
    </tr>
    @endif
    @if(!empty($data->plot_no))
    <tr>
        <td>Plot Number</td>
        <td>{{ $data->plot_no }}</td>
    </tr>
    @endif
    @if(!empty($data->company_telephone))
    <tr>
        <td>Company Telephone</td>
        <td>{{ $data->company_telephone }}</td>
    </tr>
    @endif
    @if(!empty($data->company_owner_name))
    <tr>
        <td>Company Owner</td>
        <td>{{ $data->company_owner_name }}</td>
    </tr>
    @endif
    @if(!empty($data->company_owner_telephone))
    <tr>
        <td>Owner Telephone</td>
        <td>{{ $data->company_owner_telephone }}</td>
    </tr>
    @endif
    @endif

    @php
$protectionSys = !empty($data->protection_system) ? getProtectionSystemName($data->protection_system) : null;
    @endphp
    @if(!empty($data->effective_date) || !empty($data->expiry_date) || !empty($data->insurance_limit) || !empty($protectionSys) || !empty($data->inception_date) || !empty($data->insurance_expiry_date))
    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Insurance Details
        </td>
    </tr>
    @if(!empty($data->effective_date))
    <tr>
        <td>Effective Date</td>
        <td>{{ date('m-d-Y', strtotime($data->effective_date)) }}</td>
    </tr>
    @endif
    @if(!empty($data->expiry_date))
    <tr>
        <td>Expiry Date</td>
        <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
    </tr>
    @endif
    @if(!empty($data->insurance_limit))
    <tr>
        <td>Insurance Limit</td>
        <td>{{ $data->insurance_limit }}</td>
    </tr>
    @endif
    @if(!empty($protectionSys) && $protectionSys !== '-')
    <tr>
        <td>Protection System</td>
        <td>{{ $protectionSys }}</td>
    </tr>
    @endif
    @if(!empty($data->inception_date))
    <tr>
        <td>Inception Date</td>
        <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
    </tr>
    @endif
    @if(!empty($data->insurance_expiry_date))
    <tr>
        <td>Insurance Expiry Date</td>
        <td>{{ date('m-d-Y', strtotime($data->insurance_expiry_date)) }}</td>
    </tr>
    @endif
    @endif
@php
    // Status columns store 1 = yes, 0 = no; the detail answer is shown only for "Yes"
    $declarationRows = [];
    foreach ([
        ['Partner Company Status', 'partner_company_status', null, null],
        ['Authorized Insurance Policy Status', 'authorized_insurance_police_status', 'Authorized Position', 'authorized_position'],
        ['Company Registration Status', 'auth_company_register_status', null, null],
        ['Previous Insurance Policy', 'provious_insurance_policy', 'Previous Insurance Company / Expiry Date', 'previous_insurance_details'],
        ['Insurance Declined', 'insurance_declined_issue_status', 'Decline Reason / Insurance Company', 'insurance_declined_details'],
        ['Claims in the Last 5 Years', 'claims_5_year_status', 'Claim / Accident Details', 'claims_5_year_details'],
    ] as [$label, $statusField, $detailLabel, $detailField]) {
        $status = $data->{$statusField} ?? null;
        if ($status === null || $status === '') {
            continue;
        }

        $declarationRows[$label] = $status == 1 ? 'Yes' : 'No';

        if ($status == 1 && $detailField && !empty($data->{$detailField})) {
            $declarationRows[$detailLabel] = $data->{$detailField};
        }
    }
@endphp
@if(count($declarationRows) || isset($data->renew))
    <tr>
        <td colspan="2" style="background-color: #d0d0d0;">
            Declarations
        </td>
    </tr>

    @foreach($declarationRows as $label => $value)
        <tr>
            <td>{{ $label }}</td>
            <td>{{ $value }}</td>
        </tr>
    @endforeach

    @if(isset($data->renew) && $data->renew !== '')
        <tr>
            <td>Renewal</td>
            <td>{{ $data->renew == 1 ? 'Yes' : ($data->renew == 2 ? 'No' : '') }}</td>
        </tr>
    @endif

    @if(!empty($data->old_policy_id_for_renew))
        <tr>
            <td>Previous Policy ID</td>
            <td>{{ $data->old_policy_id_for_renew }}</td>
        </tr>
    @endif
@endif


    @php
        $officeDocuments = [
            'Rent Contract' => $data->rent_contract_documents ?? null,
            'Property Photo' => $data->property_photo_documents ?? null,
            'Contents' => $data->contents_documents ?? null,
            'Owner ID' => $data->owner_id_documents ?? null,
            'Policy Issuer' => $data->policy_issuer_documents ?? null,
            'Company Owner' => $data->company_owner_documents ?? null,
            'Career / Municipality License' => $data->career_municipality_license_documents ?? null,
            'Company Tax Certificate' => $data->company_tax_certi_documents ?? null,
            'Practice Certificate' => $data->practice_documents ?? null,
        ];
        $officeDocuments = array_filter($officeDocuments, fn($value) => count(getDocumentPaths($value)));
    @endphp

    @if(count($officeDocuments))
        <tr>
            <td colspan="2" style="background-color: #d0d0d0;">
                Supporting Documents
            </td>
        </tr>
        @foreach($officeDocuments as $label => $value)
            <tr>
                <td>{{ $label }}</td>
                <td>{!! getDocumentLinks($value) !!}</td>
            </tr>
        @endforeach
    @endif

</table>


        <!-- POLICY DETAILS -->
        <table class="policy-details">
            @if(!empty($data->police_no))
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>{{ $data->police_no }}</td>
            </tr>
            @endif
            @if(!empty($data->plan_name))
            <tr>
                <td><strong>Plan Name</strong></td>
                <td>{{ $data->plan_name }}</td>
            </tr>
            @endif
            @if(!empty($fullName))
            <tr>
                <td><strong>Policy Holder</strong></td>
                <td>{{ $fullName }}</td>
            </tr>
            @endif
            @if(!empty($data->effective_date) || !empty($data->inception_date))
            <tr>
                <td><strong>Effective Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->effective_date ?? $data->inception_date)) }}</td>
            </tr>
            @endif
            @if(!empty($data->expiry_date))
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
            </tr>
            @endif
            <!-- <tr>
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr> -->
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
  @if(isset($isFinalPdf) && $isFinalPdf)
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

        @endif

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>

    </div>

</body>

</html>
