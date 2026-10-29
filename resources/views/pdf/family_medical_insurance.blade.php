<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Medical Insurance Policy</title>

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

        .family-details-grid .policy-details {
            font-size: 12px;
        }

        .family-details-grid .policy-details td {
            padding: 8px;
        }

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

    /* Full-width family medical insurance sections */
    .family-details-grid {
        width: 100%;
        margin-bottom: 20px;
    }

    .family-layout-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .family-layout-table>tbody>tr>td {
        width: 100%;
        padding: 5px;
        vertical-align: top;
    }

    .family-layout-table>tbody>tr {
        page-break-inside: avoid;
    }

    .family-details-grid .policy-summary {
        width: 100%;
        margin-bottom: 10px;
        page-break-inside: avoid;
    }

    .family-details-grid h4 {
        margin: 0 0 8px;
        font-size: 13px;
    }

    .family-details-grid .policy-details {
        width: 100%;
        margin-bottom: 0;
        table-layout: fixed;
        border-collapse: collapse;
        font-size: 13px;
    }

    .family-details-grid .policy-details td {
        padding: 5px;
        white-space: normal;
        overflow-wrap: break-word;
        word-wrap: break-word;
        vertical-align: top;
    }

    .family-details-grid .policy-details td:first-child {
        width: 48%;
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
            <div class="letterhead">
                <img src="{{ $letterHeadPath }}" alt="{{ $data->company_name ?? 'Insurance Company' }} Letterhead">
            </div>
        @endif

        <header class="policy-header">
            <img src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo)}}"
                alt="{{ $data->company_name }}" class="logo">
            <h1>Family Medical insurance Policy</h1>
        </header>



        {{-- FAMILY MEDICAL INSURANCE DETAILS: EACH SECTION IN ITS OWN ROW --}}
        <div class="family-details-grid">
            <table class="family-layout-table">
                <tbody>

                    {{-- ROW 1: POLICYHOLDER DETAILS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Policyholder Details</h4>
                                <table class="policy-details">
                                    <tbody>
                                        @if(!empty($data->first_name))
                                        <tr>
                                            <td>First Name</td>
                                            <td>{{ $data->first_name }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->last_name))
                                        <tr>
                                            <td>Last Name</td>
                                            <td>{{ $data->last_name }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->third_name))
                                        <tr>
                                            <td>Third Name</td>
                                            <td>{{ $data->third_name }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->family_name))
                                        <tr>
                                            <td>Family Name</td>
                                            <td>{{ $data->family_name }}</td>
                                        </tr>
                                        @endif
                                        @php
                                            $natName = !empty($data->nationality) ? getNationalityName($data->nationality) : null;
                                        @endphp
                                        @if(!empty($natName) && $natName !== '-')
                                        <tr>
                                            <td>Nationality</td>
                                            <td>{{ $natName }}</td>
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
                                            <td>Residence ID Number</td>
                                            <td>{{ $data->id_residence_no }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->birth_date))
                                        <tr>
                                            <td>Birth Date</td>
                                            <td>{{ date('m-d-Y', strtotime($data->birth_date)) }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->gender))
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{ $data->gender }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->marital_status))
                                        <tr>
                                            <td>Marital Status</td>
                                            <td>{{ $data->marital_status }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->occupancy_work))
                                        <tr>
                                            <td>Occupation / Work</td>
                                            <td>{{ $data->occupancy_work }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->height))
                                        <tr>
                                            <td>Height</td>
                                            <td>{{ $data->height }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->wight))
                                        <tr>
                                            <td>Weight</td>
                                            <td>{{ $data->wight }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 2: RESIDENTIAL ADDRESS --}}
                    @php
                        $cityName = !empty($data->city_id) ? getCityName($data->city_id) : null;
                        $districtName = !empty($data->district_id) ? getDistrictName($data->district_id) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Residential Address</h4>
                                <table class="policy-details">
                                    <tbody>
                                        @if(!empty($cityName) && $cityName !== '-')
                                        <tr>
                                            <td>City</td>
                                            <td>{{ $cityName }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($districtName) && $districtName !== '-')
                                        <tr>
                                            <td>District</td>
                                            <td>{{ $districtName }}</td>
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
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 3: EMPLOYMENT DETAILS --}}
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
                        <td>
                            <div class="policy-summary">
                                <h4>Employment Details</h4>
                                <table class="policy-details">
                                    <tbody>
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
                                        @elseif(!empty($data->company_company_contact))
                                            <tr>
                                                <td>Company Contact Number</td>
                                                <td>{{ $data->company_company_contact }}</td>
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
                                        @elseif(!empty($data->company_street_name))
                                            <tr>
                                                <td>Company Street Name</td>
                                                <td>{{ $data->company_street_name }}</td>
                                            </tr>
                                        @endif

                                        @if($clientInfo && !empty($clientInfo->company_building_no))
                                            <tr>
                                                <td>Company Building Number</td>
                                                <td>{{ $clientInfo->company_building_no }}</td>
                                            </tr>
                                        @elseif(!empty($data->company_building_no))
                                            <tr>
                                                <td>Company Building Number</td>
                                                <td>{{ $data->company_building_no }}</td>
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
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- ROW 4: EXISTING INSURANCE --}}
                    @php
                        $prevPolicyStatus = isset($data->existing_policy_status) && $data->existing_policy_status !== '' ? getYesNoStatus($data->existing_policy_status) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Existing Insurance Details</h4>
                                <table class="policy-details">
                                    <tbody>
                                        @if(!empty($prevPolicyStatus))
                                        <tr>
                                            <td>Existing Policy Status</td>
                                            <td>{{ $prevPolicyStatus }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->existing_policy_company_name))
                                        <tr>
                                            <td>Previous Insurance Company</td>
                                            <td>{{ $data->existing_policy_company_name }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->existing_policy_expiry_date))
                                        <tr>
                                            <td>Existing Policy Expiry</td>
                                            <td>{{ date('m-d-Y', strtotime($data->existing_policy_expiry_date)) }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->existing_policy_card))
                                        <tr>
                                            <td>Existing Insurance Card</td>
                                            <td>{!! getDocumentLinks($data->existing_policy_card) !!}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 5: MEDICAL HISTORY --}}
                    @php
                        $chronicName = !empty($data->chronic_diseases_id) ? getChronicDiseaseName($data->chronic_diseases_id) : null;
                        $prevOpStatus = isset($data->previous_operation) && $data->previous_operation !== '' ? getYesNoStatus($data->previous_operation) : null;
                        $pregStatus = isset($data->pregnant_status) && $data->pregnant_status !== '' ? getYesNoStatus($data->pregnant_status) : null;
                        $dangerStatus = isset($data->dangerous_status) && $data->dangerous_status !== '' ? getYesNoStatus($data->dangerous_status) : null;
                        $dangerName = !empty($data->dangerous_id) ? getDangerousActivityName($data->dangerous_id) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Medical History and Declarations</h4>
                                <table class="policy-details">
                                    <tbody>
                                        @if(!empty($chronicName) && $chronicName !== '-')
                                        <tr>
                                            <td>Chronic Diseases</td>
                                            <td>{{ $chronicName }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($prevOpStatus))
                                        <tr>
                                            <td>Previous Operation</td>
                                            <td>{{ $prevOpStatus }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->operation_details))
                                        <tr>
                                            <td>Operation Details</td>
                                            <td>{{ $data->operation_details }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($pregStatus))
                                        <tr>
                                            <td>Pregnancy Status</td>
                                            <td>{{ $pregStatus }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->pregnant_month))
                                        <tr>
                                            <td>Pregnancy Month</td>
                                            <td>{{ $data->pregnant_month }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($dangerStatus))
                                        <tr>
                                            <td>Dangerous Activity Status</td>
                                            <td>{{ $dangerStatus }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($dangerName) && $dangerName !== '-')
                                        <tr>
                                            <td>Dangerous Activity</td>
                                            <td>{{ $dangerName }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 6: POLICY AND COVERAGE --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Policy and Coverage Details</h4>
                                <table class="policy-details">
                                    <tbody>
                                        @if(!empty($data->police_no))
                                        <tr>
                                            <td>Policy Number</td>
                                            <td>{{ $data->police_no }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->plan_name))
                                        <tr>
                                            <td>Plan Name</td>
                                            <td>{{ $data->plan_name }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->insurance_type))
                                        <tr>
                                            <td>Insurance Type</td>
                                            <td>{{ $data->insurance_type == 1 ? 'In-Patient' : ($data->insurance_type == 2 ? 'In-Out Patient' : $data->insurance_type) }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->insurance_class))
                                        <tr>
                                            <td>Insurance Class</td>
                                            <td>{{ $data->insurance_class }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->insurance_limit))
                                        <tr>
                                            <td>Insurance Limit</td>
                                            <td>{{ $data->insurance_limit }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->inception_date))
                                        <tr>
                                            <td>Inception Date</td>
                                            <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data->expiry_date))
                                        <tr>
                                            <td>Expiry Date</td>
                                            <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 7: SUPPORTING DOCUMENTS --}}
                    @php
                        $familyDocuments = array_filter([
                            'Family Book' => $data->family_book_documents ?? null,
                            'Personal Picture' => $data->personal_picture_documents ?? null,
                            'Other Documents' => $data->other_documents ?? null,
                        ], fn($value) => count(getDocumentPaths($value)));
                    @endphp
                    @if(count($familyDocuments))
                        <tr>
                            <td>
                                <div class="policy-summary">
                                    <h4>Supporting Documents</h4>
                                    <table class="policy-details">
                                        <tbody>
                                            @foreach($familyDocuments as $label => $value)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>{!! getDocumentLinks($value) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endif

                    {{-- ROW 8: FAMILY MEMBERS --}}
                    @foreach(($members ?? []) as $memberIndex => $member)
                        @php
                            $memberNationality = is_numeric($member->nationality ?? null)
                                ? getNationalityName($member->nationality)
                                : ($member->nationality ?? null);
                            $memberChronic = getChronicDiseaseName($member->chronic_diseases_id ?? null);

                            $memberRows = array_filter([
                                'Full Name' => trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '') . ' ' . ($member->third_name ?? '') . ' ' . ($member->family_name ?? '')),
                                'Relation' => $member->relation ?? null,
                                'Nationality' => $memberNationality !== '-' ? $memberNationality : null,
                                'Nationality Number' => $member->nationality_no ?? null,
                                'Residence ID Number' => $member->id_residence_no ?? null,
                                'Birth Date' => !empty($member->birth_date) ? date('m-d-Y', strtotime($member->birth_date)) . ' (Age ' . \Carbon\Carbon::parse($member->birth_date)->age . ')' : null,
                                'Gender' => $member->gender ?? null,
                                'Marital Status' => $member->marital_status ?? null,
                                'Occupation / Work' => $member->occupancy_work ?? null,
                                'Height' => $member->height ?? null,
                                'Weight' => $member->wight ?? null,
                                'Chronic Diseases' => $memberChronic !== '-' ? $memberChronic : null,
                                'Previous Operation' => isset($member->previous_operation) ? getYesNoStatus($member->previous_operation) : null,
                                'Operation Details' => $member->operation_details ?? null,
                                'Pregnant' => isset($member->pregnant_status) ? getYesNoStatus($member->pregnant_status) : null,
                                'Pregnancy Month' => $member->pregnant_month ?? null,
                                'Dangerous Activities' => isset($member->dangerous_status) ? getYesNoStatus($member->dangerous_status) : null,
                                'Dangerous Activity' => getDangerousActivityName($member->dangerous_id ?? null),
                                'Premium' => isset($member->premium) ? number_format((float) $member->premium, 2) . ' ' . ($abbr ?? 'JOD') : null,
                            ], fn($value) => $value !== null && $value !== '' && $value !== '-');

                            $memberDocuments = array_filter([
                                'Passport Front' => $member->passport_front_id ?? null,
                                'Passport Back' => $member->passport_back_id ?? null,
                                'Family Book' => $member->family_book_documents ?? null,
                                'Personal Picture' => $member->personal_picture_documents ?? null,
                                'Other Documents' => $member->other_documents ?? null,
                            ], fn($value) => count(getDocumentPaths($value)));
                        @endphp
                        <tr>
                            <td>
                                <div class="policy-summary">
                                    <h4>Family Member {{ $memberIndex + 1 }}</h4>
                                    <table class="policy-details">
                                        <tbody>
                                            @foreach($memberRows as $label => $value)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>{{ $value }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($memberDocuments as $label => $value)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>{!! getDocumentLinks($value) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>



        <div class="table-responsive">
            <table class="policy-details stack-on-mobile">
                @if(!empty($data->police_no))
                <tr>
                    <td><strong>Policy No.</strong></td>
                    <td>({{ $data->police_no }})</td>
                </tr>
                @endif
                @if(!empty($data->plan_name))
                <tr>
                    <td><strong>Plan Name</strong></td>
                    <td>{{ $data->plan_name }}</td>
                </tr>
                @endif
                @php
                    $policyHolder = trim(($data->first_name ?? '') . ' ' . ($data->last_name ?? '') . ' ' . ($data->third_name ?? '') . ' ' . ($data->family_name ?? ''));
                @endphp
                @if(!empty($policyHolder))
                <tr>
                    <td><strong>Policy Holder</strong></td>
                    <td>{{ $policyHolder }}</td>
                </tr>
                @endif
                @if(!empty($data->inception_date))
                <tr>
                    <td><strong>Effective Date</strong></td>
                    <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
                </tr>
                @endif
                @if(!empty($data->expiry_date))
                <tr>
                    <td><strong>Expiry Date</strong></td>
                    <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
                </tr>
                @endif
                <!-- <tr>
                <td><strong>Geographical Coverage</strong></td>
                <td></td>
            </tr> -->
                <!-- <tr>
                    <td><strong>Payment Method</strong></td>
                    <td>Annual in advance</td>
                </tr> -->
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

// Net premium = main insured premium x insured persons (main + family members)
$summaryMembers = $members ?? [];
if (count($summaryMembers)) {
    if (isset($plan->main_member_premium))
        $summaryColumns['Premium per Person'] = number_format((float) $plan->main_member_premium, 2) . ' ' . $abbr;

    $summaryColumns['Insured Persons (x)'] = $plan->premium_multiplier ?? count($summaryMembers) + 1;
}

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

        @if(isset($isFinalPdf) && $isFinalPdf)

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
