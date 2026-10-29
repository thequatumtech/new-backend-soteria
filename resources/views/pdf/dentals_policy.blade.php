<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Insurance Policy</title>
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

        /* Policy covers — preserve table layout and allow horizontal scrolling */
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
            <img src="{{public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo)}}" alt="{{ $data->company_name }}" class="logo">
            <h1>Dental insurance Policy</h1>
        </header>

       {{-- APPLICATION DETAILS --}}
@php
$hasApplicant = collect([
    $data->first_name ?? null,
    $data->last_name ?? null,
    $data->third_name ?? null,
    $data->family_name ?? null,
    $data->nationality ?? null,
    $data->nationality_no ?? null,
    $data->id_residence_no ?? null,
    $data->birth_date ?? null,
    $data->gender ?? null,
    $data->marital_status ?? null,
])->contains(fn($value) => !empty($value));

$hasResidence = collect([
    $data->place_residence ?? null,
    $data->city_name ?? null,
    $data->district_name ?? null,
    $data->street_name ?? null,
    $data->building_no ?? null,
])->contains(fn($value) => !empty($value));

$hasEmployment = collect([
    $data->occupancy_work ?? null,
    $data->company_name ?? null,
    $data->position ?? null,
    $data->work_nature ?? null,
    $data->company_city_name ?? null,
    $data->company_district_name ?? null,
    $data->company_street_name ?? null,
    $data->company_building_no ?? null,
    $data->company_contact ?? null,
])->contains(fn($value) => !empty($value));

$insuranceLimit = !empty($data->insurance_limit)
    ? $data->insurance_limit
    : ($purchase->policy_plan_limit ?? null);

$hasInsurance = !empty($data->plan_name)
    || !empty($insuranceLimit)
    || !empty($data->inception_date)
    || !empty($data->expiry_date);
@endphp

<div class="policy-summary">
    @if($hasApplicant || $hasResidence || $hasEmployment || $hasInsurance || !empty($data->documents))
        <h4>Application Details</h4>

        <div class="table-responsive">
            <table class="policy-details">
                <tbody>
                    {{-- APPLICANT INFORMATION --}}
                    @if($hasApplicant)
                        <tr>
                            <td colspan="2" style="background-color:#d0d0d0;">
                                <strong>Applicant Information</strong>
                            </td>
                        </tr>

                        @if(!empty($data->first_name) || !empty($data->last_name) || !empty($data->third_name) || !empty($data->family_name))
                            <tr>
                                <td>Full Name</td>
                                <td>{{ collect([$data->first_name ?? null, $data->last_name ?? null, $data->third_name ?? null, $data->family_name ?? null])->filter()->implode(' ') }}</td>
                            </tr>
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
                    @endif

                    {{-- RESIDENCE INFORMATION --}}
                    @if($hasResidence)
                        <tr>
                            <td colspan="2" style="background-color:#d0d0d0;">
                                <strong>Residence Information</strong>
                            </td>
                        </tr>

                        @if(!empty($data->place_residence))
                            <tr><td>Place of Residence</td><td>{{ $data->place_residence }}</td></tr>
                        @endif

                        @if(!empty($data->city_name))
                            <tr><td>City</td><td>{{ $data->city_name }}</td></tr>
                        @endif

                        @if(!empty($data->district_name))
                            <tr><td>District</td><td>{{ $data->district_name }}</td></tr>
                        @endif

                        @if(!empty($data->street_name))
                            <tr><td>Street Name</td><td>{{ $data->street_name }}</td></tr>
                        @endif

                        @if(!empty($data->building_no))
                            <tr><td>Building Number</td><td>{{ $data->building_no }}</td></tr>
                        @endif
                    @endif

                    
                    {{-- EMPLOYMENT INFORMATION --}}
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

                        $employmentType = $clientInfo->employment_type ?? null;
                        $occupation = $data->occupancy_work ?? ($clientInfo->occupation->name ?? null);
                        $companyName = $clientInfo->company_name ?? ($data->company_name ?? null);
                        $position = $clientInfo->position ?? ($data->position ?? null);
                        $workNature = $clientInfo->work_nature ?? ($data->work_nature ?? null);
                        $companyContact = $clientInfo->company_contact_no ?? ($data->company_contact ?? null);
                        $companyStreet = $clientInfo->company_street_name ?? ($data->company_street_name ?? null);
                        $companyBuilding = $clientInfo->company_building_no ?? ($data->company_building_no ?? null);

                        $hasEmploymentInfo = collect([
                            $employmentType,
                            $occupation,
                            $companyName,
                            $position,
                            $workNature,
                            $companyContact,
                            $companyStreet,
                            $companyBuilding,
                            $clientCompanyCity,
                            $clientCompanyDistrict,
                        ])->contains(fn($value) => !empty($value) && $value !== '-');
                    @endphp

                    @if($hasEmploymentInfo)
                        <tr>
                            <td colspan="2" style="background-color:#d0d0d0;">
                                <strong>Employment Information</strong>
                            </td>
                        </tr>

                        @if(!empty($employmentType))
                            <tr>
                                <td>Employment Type</td>
                                <td>{{ $employmentType }}</td>
                            </tr>
                        @endif

                        @if(!empty($occupation))
                            <tr>
                                <td>Occupation / Work</td>
                                <td>{{ $occupation }}</td>
                            </tr>
                        @endif

                        @if(!empty($companyName))
                            <tr>
                                <td>Company Name</td>
                                <td>{{ $companyName }}</td>
                            </tr>
                        @endif

                        @if(!empty($position))
                            <tr>
                                <td>Position</td>
                                <td>{{ $position }}</td>
                            </tr>
                        @endif

                        @if(!empty($workNature))
                            <tr>
                                <td>Nature of Work</td>
                                <td>{{ $workNature }}</td>
                            </tr>
                        @endif

                        @if(!empty($companyContact))
                            <tr>
                                <td>Company Contact Number</td>
                                <td>{{ $companyContact }}</td>
                            </tr>
                        @endif

                        @if(!empty($companyStreet))
                            <tr>
                                <td>Company Street Name</td>
                                <td>{{ $companyStreet }}</td>
                            </tr>
                        @endif

                        @if(!empty($companyBuilding))
                            <tr>
                                <td>Company Building Number</td>
                                <td>{{ $companyBuilding }}</td>
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


                    {{-- INSURANCE INFORMATION --}}
                    @if($hasInsurance)
                        <tr>
                            <td colspan="2" style="background-color:#d0d0d0;">
                                <strong>Insurance Information</strong>
                            </td>
                        </tr>

                        @if(!empty($data->plan_name))
                            <tr><td>Insurance Plan</td><td>{{ $data->plan_name }}</td></tr>
                        @endif

                        @if(!empty($insuranceLimit))
                            <tr>
                                <td>Insurance Limit</td>
                                <td>{{ number_format((float) $insuranceLimit, 2) }} {{ $abbr ?? 'JOD' }}</td>
                            </tr>
                        @endif

                        @if(!empty($data->inception_date))
                            <tr><td>Effective Date</td><td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td></tr>
                        @endif

                        @if(!empty($data->expiry_date))
                            <tr><td>Expiry Date</td><td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td></tr>
                        @endif
                    @endif

                    {{-- SUPPORTING DOCUMENTS --}}
                    @if(!empty($data->documents))
                        <tr>
                            <td colspan="2" style="background-color:#d0d0d0;">
                                <strong>Supporting Documents</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Documents</td>
                            <td>{!! getDocumentLinks($data->documents) !!}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endif
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
            </table>
        </div>

        {{-- ========================= PREMIUM SUMMARY (vertical label|value — matches Personal Accident) ========================= --}}
        @php
$abbr = $abbr ?? 'JOD';

$columns = [];

if (!empty($purchase->policy_plan_limit))
    $columns['Limit'] = number_format((float) $purchase->policy_plan_limit, 2) . ' ' . $abbr;

if (!empty($purchase->net_premium))
    $columns['Net Premium'] = number_format((float) $purchase->net_premium, 2) . ' ' . $abbr;

$fees_label = 'Issuance Fees' . ($data->fees > 0 ? ' (' . $data->fees . '%)' : '');
if (!empty($purchase->fees))
    $columns[$fees_label] = number_format((float) $purchase->fees, 2) . ' ' . $abbr;

$stamps_label = 'Stamps' . ($data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');
if (!empty($purchase->stamps))
    $columns[$stamps_label] = number_format((float) $purchase->stamps, 2) . ' ' . $abbr;

$sales_tax_label = 'Sales Tax' . ($data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');
if (!empty($purchase->sales_tax))
    $columns[$sales_tax_label] = number_format((float) $purchase->sales_tax, 2) . ' ' . $abbr;

$cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');
if (!empty($purchase->cbj))
    $columns[$cbj_label] = number_format((float) $purchase->cbj, 2) . ' ' . $abbr;

$cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($data->sales_tax_cbj > 0 ? ' (' . $data->sales_tax_cbj . '%)' : '');
if (!empty($purchase->sales_tax_cbj))
    $columns[$cbj_tax_label] = number_format((float) $purchase->sales_tax_cbj, 2) . ' ' . $abbr;

if (!empty($purchase->gross_premium))
    $columns['Gross Premium'] = number_format((float) $purchase->gross_premium, 2) . ' ' . $abbr;
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

        @if(!empty($plan->policy_covers) && count($plan->policy_covers) > 0)
        <div class="policy-summary">
            <h4>Policy Covers</h4>
            <div class="table-responsive">
                <table class="policy-details">
                    <tr>
                        <td><strong>Name of Cover</strong></td>
                        <td><strong>Limit</strong></td>
                        <td><strong>Deductible</strong></td>
                    </tr>
                    @foreach($plan->policy_covers as $cover)
                    <tr>
                        <td>{{ $cover->cover_name }}</td>
                        <td>{{ $cover->cover_limit }}</td>
                        <td>{{ $cover->cover_deductible }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>


    @if(isset($isFinalPdf) && $isFinalPdf)

            @php
    $userSignaturePath = null;
    if (!empty($data->user_signature)) {
        $path = public_path($data->user_signature);
        if (file_exists($path)) {
            $userSignaturePath = $path;
        }
    }

    $companyStampPath = null;
    if (!empty($data->company_stamp)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp);
        if (file_exists($path)) {
            $companyStampPath = $path;
        }
    }

    $authorizedSignaturePath = null;
    if (!empty($data->authorized_signature)) {
        $path = public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature);
        if (file_exists($path)) {
            $authorizedSignaturePath = $path;
        }
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

    @endif

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>

    </div>

</body>

</html>
