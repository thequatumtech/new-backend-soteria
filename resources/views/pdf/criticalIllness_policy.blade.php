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

        @if(isset($isFinalPdf) && $isFinalPdf && !empty($letterHeadPath))
            <div class="letterhead"> <img src="{{ $letterHeadPath }}"
                    alt="{{ $data->company_name ?? 'Insurance Company' }} Letterhead"> </div>
        @endif

        <header class="policy-header">
            <img src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo)}}"
                alt="{{ $data->company_name }}" class="logo">
            <h1>Critical Illness insurance Policy</h1>
        </header>


{{-- ================= APPLICATION DETAILS ================= --}}
<div class="policy-summary">
    <h4>Application Details</h4>

    <div class="table-responsive">
        <table class="policy-details">

            @php
$applicantName = trim(implode(' ', array_filter([
    $data->first_name ?? '',
    $data->last_name ?? '',
    $data->third_name ?? '',
    $data->family_name ?? '',
])));

$beneficiaryName = trim(implode(' ', array_filter([
    $data->beneficiary_first_name ?? '',
    $data->beneficiary_last_name ?? '',
    $data->beneficiary_third_name ?? '',
])));
$companyCity = $data->company_city_name ?? null;

if (empty($companyCity) || trim((string) $companyCity) === '-') {
    $companyCity = !empty($data->company_city_id)
        ? getCityName($data->company_city_id)
        : (!empty($data->city_id_2)
            ? getCityName($data->city_id_2)
            : null);
}

$companyDistrict = $data->company_district_name ?? null;

if (empty($companyDistrict) || trim((string) $companyDistrict) === '-') {
    $companyDistrict = !empty($data->company_district_id)
        ? getDistrictName($data->company_district_id)
        : null;
}

// Remove empty values and placeholder dashes
$companyAddressParts = [
    $data->company_street_name ?? null,
    !empty($data->company_building_no)
    ? 'Building ' . $data->company_building_no
    : null,
    $companyCity,
    $companyDistrict,
];

$companyAddress = collect([
    $data->company_street_name ?? null,
    !empty($data->company_building_no)
    ? 'Building ' . $data->company_building_no
    : null,
    $companyCity,
    $companyDistrict,
])
    ->map(fn($value) => trim((string) $value))
    ->filter(fn($value) => $value !== '' && $value !== '-')
    ->implode(', ');

            @endphp

            {{-- Applicant Information --}}
            @if(
    $applicantName ||
    !empty($data->nationality) ||
    !empty($data->nationality_no) ||
    !empty($data->id_residence_no) ||
    !empty($data->birth_date) ||
    !empty($data->gender) ||
    !empty($data->marital_status) ||
    !empty($data->place_residence)
)
                <tr>
                    <td colspan="2" style="background-color: #d0d0d0;">
                        <strong>Applicant Information</strong>
                    </td>
                </tr>

                @if($applicantName)
                    <tr><td>Full Name</td><td>{{ $applicantName }}</td></tr>
                @endif

                @if(!empty($data->nationality))
                    <tr><td>Nationality</td><td>{{ $data->nationality }}</td></tr>
                @endif

                @if(!empty($data->nationality_no))
                    <tr><td>Nationality Number</td><td>{{ $data->nationality_no }}</td></tr>
                @endif

                @if(!empty($data->id_residence_no))
                    <tr><td>Residence ID Number</td><td>{{ $data->id_residence_no }}</td></tr>
                @endif

                @if(!empty($data->birth_date))
                    <tr><td>Date of Birth</td><td>{{ date('m-d-Y', strtotime($data->birth_date)) }}</td></tr>
                @endif

                @if(!empty($data->gender))
                    <tr><td>Gender</td><td>{{ $data->gender }}</td></tr>
                @endif

                @if(!empty($data->marital_status))
                    <tr><td>Marital Status</td><td>{{ $data->marital_status }}</td></tr>
                @endif

                @if(!empty($data->place_residence))
                    <tr><td>Place of Residence</td><td>{{ $data->place_residence }}</td></tr>
                @endif
            @endif

            @php
$normalizeDocuments = function ($value) {
    if (empty($value)) {
        return [];
    }

    $decoded = json_decode($value, true);

    if (is_array($decoded)) {
        return $decoded;
    }

    if (is_string($decoded) && $decoded !== '') {
        return [$decoded];
    }

    return [$value];
};

$passportDocuments = $normalizeDocuments(
    $data->passport_id_documents ?? null
);

$insuredDocuments = $normalizeDocuments(
    $data->insured_documents ?? null
);
            @endphp

            {{-- Beneficiary Information --}}
            @if($beneficiaryName)
                <tr>
                    <td colspan="2" style="background-color: #d0d0d0;">
                        <strong>Beneficiary Information</strong>
                    </td>
                </tr>
                <tr>
                    <td>Beneficiary Name</td>
                    <td>{{ $beneficiaryName }}</td>
                </tr>
            @endif

            @php
                $clientInfo = $data->client_details ?? null;

                $clientCompanyCity = null;
                $clientCompanyDistrict = null;

                if ($clientInfo) {
                    if (!empty($clientInfo->company_city_id)) {
                        $clientCompanyCity = getCityName($clientInfo->company_city_id);
                    }

                    if (empty($clientCompanyCity) || $clientCompanyCity === '-') {
                        $clientCompanyCity = $clientInfo->company_city->name ?? null;
                    }

                    if (!empty($clientInfo->company_district_id)) {
                        $clientCompanyDistrict = getDistrictName($clientInfo->company_district_id);
                    }

                    if (empty($clientCompanyDistrict) || $clientCompanyDistrict === '-') {
                        $clientCompanyDistrict = $clientInfo->company_district->name ?? null;
                    }
                }
            @endphp

            @if(
    ($clientInfo && (
        !empty($clientInfo->employment_type) ||
        !empty($clientInfo->company_name) ||
        !empty($clientInfo->position) ||
        !empty($clientInfo->work_nature) ||
        !empty($clientInfo->company_contact_no) ||
        !empty($clientInfo->company_street_name) ||
        !empty($clientInfo->company_building_no) ||
        !empty($clientCompanyCity) ||
        !empty($clientCompanyDistrict)
    )) ||
    !empty($data->occupancy_work) ||
    !empty($data->company_name)
)
                <tr>
                    <td colspan="2" style="background-color: #d0d0d0;">
                        <strong>Employment Information</strong>
                    </td>
                </tr>

                @if($clientInfo && !empty($clientInfo->employment_type))
                    <tr>
                        <td>Employment Type</td>
                        <td>{{ $clientInfo->employment_type }}</td>
                    </tr>
                @endif

                @if(!empty($data->occupancy_work))
                    <tr>
                        <td>Occupation / Work</td>
                        <td>{{ $data->occupancy_work }}</td>
                    </tr>
                @elseif($clientInfo && !empty($clientInfo->occupation))
                    <tr>
                        <td>Occupation / Work</td>
                        <td>{{ $clientInfo->occupation->name ?? '' }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->company_name))
                    <tr>
                        <td>Company Name</td>
                        <td>{{ $clientInfo->company_name }}</td>
                    </tr>
                @elseif(!empty($data->company_name))
                    <tr>
                        <td>Company Name</td>
                        <td>{{ $data->company_name }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->position))
                    <tr>
                        <td>Position</td>
                        <td>{{ $clientInfo->position }}</td>
                    </tr>
                @elseif(!empty($data->position))
                    <tr>
                        <td>Position</td>
                        <td>{{ $data->position }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->work_nature))
                    <tr>
                        <td>Nature of Work</td>
                        <td>{{ $clientInfo->work_nature }}</td>
                    </tr>
                @elseif(!empty($data->work_nature))
                    <tr>
                        <td>Nature of Work</td>
                        <td>{{ $data->work_nature }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->company_contact_no))
                    <tr>
                        <td>Company Contact Number</td>
                        <td>{{ $clientInfo->company_contact_no }}</td>
                    </tr>
                @elseif(!empty($data->company_contact))
                    <tr>
                        <td>Company Contact Number</td>
                        <td>{{ $data->company_contact }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->company_street_name))
                    <tr>
                        <td>Company Street Name</td>
                        <td>{{ $clientInfo->company_street_name }}</td>
                    </tr>
                @endif

                @if($clientInfo && !empty($clientInfo->company_building_no))
                    <tr>
                        <td>Company Building Number</td>
                        <td>{{ $clientInfo->company_building_no }}</td>
                    </tr>
                @endif

                @if(!empty($clientCompanyCity) && $clientCompanyCity !== '-')
                    <tr>
                        <td>Company City</td>
                        <td>{{ $clientCompanyCity }}</td>
                    </tr>
                @endif

                @if(!empty($clientCompanyDistrict) && $clientCompanyDistrict !== '-')
                    <tr>
                        <td>Company District</td>
                        <td>{{ $clientCompanyDistrict }}</td>
                    </tr>
                @endif
            @endif

            {{-- Medical Information --}}
            @if(
    !empty($data->height) ||
    !empty($data->wight) ||
    !empty($chronicDiseases) ||
    !empty($data->previous_operation) ||
    !empty($data->operation_details)
)
                                                                                                <tr>
                                                                                                    <td colspan="2" style="background-color: #d0d0d0;">
                                                                                                        <strong>Medical Information</strong>
                                                                                                    </td>
                                                                                                </tr>

                                                                                                @if(!empty($data->height))
                                                                                                    <tr><td>Height</td><td>{{ $data->height }}</td></tr>
                                                                                                @endif

                                                                                                @if(!empty($data->wight))
                                                                                                    <tr><td>Weight</td><td>{{ $data->wight }}</td></tr>
                                                                                                @endif

                                                                                        @php
    $chronicDiseases = getChronicDiseaseName(
        $data->chronic_diseases_id ?? null
    );
                                                                                        @endphp

                                                                                        @if($chronicDiseases !== '-')
                                                                                            <tr>
                                                                                                <td>Chronic Diseases</td>
                                                                                                <td>{{ $chronicDiseases }}</td>
                                                                                            </tr>
                                                                                        @endif

                                                                                            @if(isset($data->previous_operation) && $data->previous_operation !== '')
                                                                                                <tr>
                                                                                                    <td>Previous Operation</td>
                                                                                                    <td>{{ $data->previous_operation == 1 ? 'Yes' : 'No' }}</td>
                                                                                                </tr>
                                                                                            @endif

                                                                                                @if(!empty($data->operation_details))
                                                                                                    <tr><td>Operation Details</td><td>{{ $data->operation_details }}</td></tr>
                                                                                                @endif
            @endif

            {{-- Insurance Information --}}
            @if(
    !empty($data->previous_insurance_policy) ||
    !empty($data->previous_insurance_policy_details) ||
    isset($data->insurance_amount) ||
    !empty($data->plan_name) ||
    !empty($data->inception_date) ||
    !empty($data->expiry_date)
)
                                <tr>
                                    <td colspan="2" style="background-color: #d0d0d0;">
                                        <strong>Insurance Information</strong>
                                    </td>
                                </tr>

                            @if(isset($data->previous_insurance_policy) && $data->previous_insurance_policy !== '')
                                <tr>
                                    <td>Previous Insurance Policy</td>
                                    <td>{{ $data->previous_insurance_policy == 1 ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endif

                                @if(!empty($data->previous_insurance_policy_details))
                                    <tr><td>Previous Policy Details</td><td>{{ $data->previous_insurance_policy_details }}</td></tr>
                                @endif

                                @if(isset($data->insurance_amount) && $data->insurance_amount !== '')
                                    <tr>
                                        <td>Insurance Amount</td>
                                        <td>{{ number_format((float) $data->insurance_amount, 2) . ' ' . ($abbr ?? 'JOD') }}</td>
                                    </tr>
                                @endif

                                @if(!empty($data->plan_name))
                                    <tr><td>Insurance Plan</td><td>{{ $data->plan_name }}</td></tr>
                                @endif

                                @if(!empty($data->inception_date))
                                    <tr><td>Inception Date</td><td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td></tr>
                                @endif

                                @if(!empty($data->expiry_date))
                                    <tr><td>Expiry Date</td><td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td></tr>
                                @endif
            @endif



        @if(count($passportDocuments) || count($insuredDocuments))
            <tr>
                <td colspan="2" style="background-color: #d0d0d0;">
                    <strong>Supporting Documents</strong>
                </td>
            </tr>

            @foreach($passportDocuments as $index => $document)
                <tr>
                    <td>Passport / ID Document {{ $index + 1 }}</td>
                    <td>
                        {{ storage_path('app/public/' . $document) }}
                    </td>
                </tr>
            @endforeach

            @foreach($insuredDocuments as $index => $document)
                <tr>
                    <td>Insured Document {{ $index + 1 }}</td>
                    <td>
                        {{ storage_path('app/public/' . $document) }}
                    </td>
                </tr>
            @endforeach
        @endif

        </table>
    </div>
</div>
{{-- ================= END APPLICATION DETAILS ================= --}}

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

            @if(isset($isFinalPdf) && $isFinalPdf)
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
                            <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
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

                        <img src="{{ $userSignaturePath }}" alt="User Signature" class="signature-image">

                    </td>


                    {{-- AUTHORIZED SIGNATURE --}}
                    <td>

                        <div class="signature-title">
                            Authorized Signature
                        </div>

                        @if($authorizedSignaturePath)
                            <img src="{{ $authorizedSignaturePath }}" alt="Authorized Signature" class="signature-image">
                        @endif

                    </td>

                </tr>

            </table>

        @else


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
                            <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                        @endif

                    </td>


                    {{-- AUTHORIZED SIGNATURE --}}
                    <td>

                        <div class="signature-title">
                            Authorized Signature
                        </div>

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
