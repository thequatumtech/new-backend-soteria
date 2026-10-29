<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Insurance Policy</title>
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

        /* Policy covers — preserve table layout */
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
            <h1>Life Insurance Policy</h1>
        </header>

{{-- POLICYHOLDER DETAILS --}}
<div class="policy-summary">
    <h4>Policyholder Details</h4>
    <table class="policy-details">
        <tbody>
            @if(!empty($data->first_name))
            <tr><td>First Name</td><td>{{ $data->first_name }}</td></tr>
            @endif
            @if(!empty($data->last_name))
            <tr><td>Last Name</td><td>{{ $data->last_name }}</td></tr>
            @endif
            @if(!empty($data->third_name))
            <tr><td>Third Name</td><td>{{ $data->third_name }}</td></tr>
            @endif
            @if(!empty($data->family_name))
            <tr><td>Family Name</td><td>{{ $data->family_name }}</td></tr>
            @endif
            @php
                $natName = !empty($data->nationality) ? getNationalityName($data->nationality) : null;
            @endphp
            @if(!empty($natName) && $natName !== '-')
            <tr><td>Nationality</td><td>{{ $natName }}</td></tr>
            @endif
            @if(!empty($data->nationality_no))
            <tr><td>Nationality Number</td><td>{{ $data->nationality_no }}</td></tr>
            @endif
            @if(!empty($data->id_residence_no))
            <tr><td>Residence ID Number</td><td>{{ $data->id_residence_no }}</td></tr>
            @endif
            @if(!empty($data->birth_date))
            <tr><td>Birth Date</td><td>{{ date('m-d-Y', strtotime($data->birth_date)) }}</td></tr>
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
            @if(!empty($data->occupancy_work))
            <tr><td>Occupation / Work</td><td>{{ $data->occupancy_work }}</td></tr>
            @endif
            @if(isset($data->american_notionality_status) && $data->american_notionality_status !== '')
            <tr><td>American Nationality Status</td><td>{{ getYesNoStatus($data->american_notionality_status) }}</td></tr>
            @endif
            @if(!empty($data->beneficiary_first_name))
            <tr><td>Beneficiary First Name</td><td>{{ $data->beneficiary_first_name }}</td></tr>
            @endif
            @if(!empty($data->beneficiary_last_name))
            <tr><td>Beneficiary Last Name</td><td>{{ $data->beneficiary_last_name }}</td></tr>
            @endif
            @if(!empty($data->beneficiary_third_name))
            <tr><td>Beneficiary Third Name</td><td>{{ $data->beneficiary_third_name }}</td></tr>
            @endif
        </tbody>
    </table>
</div>

{{-- RESIDENTIAL ADDRESS --}}
@php
    $countryName = !empty($data->country_id) ? getCountryName($data->country_id) : null;
    $cityName = !empty($data->city_id) ? getCityName($data->city_id) : null;
    $districtName = !empty($data->district_id) ? getDistrictName($data->district_id) : null;
@endphp
<div class="policy-summary">
    <h4>Residential Address</h4>
    <table class="policy-details">
        <tbody>
            @if(!empty($countryName) && $countryName !== '-')
            <tr><td>Country</td><td>{{ $countryName }}</td></tr>
            @endif
            @if(!empty($cityName) && $cityName !== '-')
            <tr><td>City</td><td>{{ $cityName }}</td></tr>
            @endif
            @if(!empty($districtName) && $districtName !== '-')
            <tr><td>District</td><td>{{ $districtName }}</td></tr>
            @endif
            @if(!empty($data->street_name))
            <tr><td>Street Name</td><td>{{ $data->street_name }}</td></tr>
            @endif
            @if(!empty($data->building_no))
            <tr><td>Building Number</td><td>{{ $data->building_no }}</td></tr>
            @endif
        </tbody>
    </table>
</div>

{{-- EMPLOYMENT DETAILS (from client profile, only for employed / self employed) --}}
@php
    $clientInfo = $data->client_details ?? null;

    $employmentType = $clientInfo->employment_type ?? null;
    $normalizedEmploymentType = str_replace(['-', '_'], ' ', strtolower(trim((string) $employmentType)));
    $showEmployment = in_array($normalizedEmploymentType, ['employed', 'self employed']);

    $employmentRows = [];

    if ($showEmployment) {
        $companyCity = !empty($clientInfo->company_city_id) ? getCityName($clientInfo->company_city_id) : null;
        $companyDistrict = !empty($clientInfo->company_district_id) ? getDistrictName($clientInfo->company_district_id) : null;

        $employmentRows = array_filter([
            'Employment Type' => ucwords($normalizedEmploymentType),
            'Occupation / Work' => $clientInfo->occupation->name ?? null,
            'Company Name' => $clientInfo->company_name ?? null,
            'Position' => $clientInfo->position ?? null,
            'Nature of Work' => $clientInfo->work_nature ?? null,
            'Company City' => $companyCity !== '-' ? $companyCity : null,
            'Company District' => $companyDistrict !== '-' ? $companyDistrict : null,
            'Company Street' => $clientInfo->company_street_name ?? null,
            'Company Building Number' => $clientInfo->company_building_no ?? null,
            'Company Contact' => $clientInfo->company_contact_no ?? null,
        ], fn($value) => !empty($value));
    }
@endphp

@if($showEmployment)
<div class="policy-summary">
    <h4>Employment Details</h4>
    <table class="policy-details">
        <tbody>
            @foreach($employmentRows as $label => $value)
                <tr><td>{{ $label }}</td><td>{{ $value }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- MEDICAL HISTORY --}}
@php
    $chronicName = !empty($data->chronic_diseases_id) ? getChronicDiseaseName($data->chronic_diseases_id) : null;
    $prevOpStatus = isset($data->previous_operation) && $data->previous_operation !== '' ? getYesNoStatus($data->previous_operation) : null;
    $declinedStatus = isset($data->company_declined_policy) && $data->company_declined_policy !== '' ? getYesNoStatus($data->company_declined_policy) : null;
    $existingLifeStatus = isset($data->exiting_life_insur) && $data->exiting_life_insur !== '' ? getYesNoStatus($data->exiting_life_insur) : null;
@endphp
<div class="policy-summary">
    <h4>Medical History and Declarations</h4>
    <table class="policy-details">
        <tbody>
            @if(!empty($data->height))
            <tr><td>Height</td><td>{{ $data->height }}</td></tr>
            @endif
            @if(!empty($data->wight))
            <tr><td>Weight</td><td>{{ $data->wight }}</td></tr>
            @endif
            @if(!empty($chronicName) && $chronicName !== '-')
            <tr><td>Chronic Diseases</td><td>{{ $chronicName }}</td></tr>
            @endif
            @if(!empty($prevOpStatus))
            <tr><td>Previous Operation</td><td>{{ $prevOpStatus }}</td></tr>
            @endif
            @if(!empty($data->operation_details))
            <tr><td>Operation Details</td><td>{{ $data->operation_details }}</td></tr>
            @endif
            @if(!empty($declinedStatus))
            <tr><td>Company Declined Policy</td><td>{{ $declinedStatus }}</td></tr>
            @endif
            @if(!empty($data->declined_policy_details))
            <tr><td>Declined Policy Details</td><td>{{ $data->declined_policy_details }}</td></tr>
            @endif
            @if(!empty($existingLifeStatus))
            <tr><td>Existing Life Insurance</td><td>{{ $existingLifeStatus }}</td></tr>
            @endif
            @if(!empty($data->exiting_life_insur_details))
            <tr><td>Existing Life Insurance Details</td><td>{{ $data->exiting_life_insur_details }}</td></tr>
            @endif
        </tbody>
    </table>
</div>

{{-- BENEFICIARY DETAILS --}}
@if(!empty($data->beneficiary_first_name) || !empty($data->beneficiary_last_name) || !empty($data->beneficiary_third_name))
<div class="policy-summary">
    <h4>Beneficiary Details</h4>
    <table class="policy-details">
        <tbody>
            @if(!empty($data->beneficiary_first_name))
            <tr><td>First Name</td><td>{{ $data->beneficiary_first_name }}</td></tr>
            @endif
            @if(!empty($data->beneficiary_last_name))
            <tr><td>Last Name</td><td>{{ $data->beneficiary_last_name }}</td></tr>
            @endif
            @if(!empty($data->beneficiary_third_name))
            <tr><td>Third Name</td><td>{{ $data->beneficiary_third_name }}</td></tr>
            @endif
        </tbody>
    </table>
</div>
@endif

{{-- LIFE INSURANCE COVERAGE --}}
@php
    $insurancePeriod = $data->periods_val ?? (!empty($data->insurance_period) ? getInsurancePeriodName($data->insurance_period) : null);
@endphp
<div class="policy-summary">
    <h4>Life Insurance Coverage Details</h4>
    <table class="policy-details">
        <tbody>
            @if(!empty($data->insurance_amount))
            <tr><td>Insurance Amount</td><td>{{ $data->insurance_amount }}</td></tr>
            @endif
            @if(!empty($data->effective_date))
            <tr><td>Effective Date</td><td>{{ date('m-d-Y', strtotime($data->effective_date)) }}</td></tr>
            @endif
            @if(!empty($insurancePeriod))
            <tr><td>Insurance Period (Years)</td><td>{{ $insurancePeriod }}</td></tr>
            @endif
            @if(!empty($data->inception_date))
            <tr><td>Inception Date</td><td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td></tr>
            @endif
            @if(!empty($data->expiry_date))
            <tr><td>Expiry Date</td><td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td></tr>
            @endif
            @if(isset($data->renew) && $data->renew !== '')
            <tr><td>Renewal</td><td>{{ !empty($data->renew) ? 'Yes' : 'No' }}</td></tr>
            @endif
            @if(!empty($data->old_policy_id_for_renew))
            <tr><td>Previous Policy ID</td><td>{{ $data->old_policy_id_for_renew }}</td></tr>
            @endif
        </tbody>
    </table>
</div>

{{-- SUPPORTING DOCUMENTS --}}
@php
    $lifeDocuments = [
        'Photo' => getDocumentPaths($data->photo_documents ?? null),
        'Insured Document' => getDocumentPaths($data->insured_documents ?? null),
        'Family Book' => getDocumentPaths($data->family_book_documents ?? null),
    ];
@endphp

@if(count(array_merge(...array_values($lifeDocuments))))
<div class="policy-summary">
    <h4>Supporting Documents</h4>
    <table class="policy-details">
        <tbody>
            @foreach($lifeDocuments as $label => $documents)
                @foreach($documents as $index => $document)
                    @php $documentUrl = getDocumentUrl($document); @endphp
                    <tr>
                        <td>{{ $label }} {{ $index + 1 }}</td>
                        <td>
                            @if($documentUrl)
                                <a href="{{ $documentUrl }}">{{ $documentUrl }}</a>
                            @else
                                Document not found
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endif




        <!-- POLICY DETAILS -->
        <table class="policy-details">
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
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr> -->
        </table>

        <!-- POLICY SUMMARY -->
        <div class="policy-summary">
            <h4>Policy Summary</h4>
            <div class="table-scroll">
                <table class="premium-summary-table">
                    @isset($plan->limit)
                    <tr>
                        <td>Limit</td>
                        <td>{{ number_format((float) $plan->limit, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset

                    @isset($plan->net_premium_amount)
                    <tr>
                        <td>Net Premium</td>
                        <td>{{ number_format((float) $plan->net_premium_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset

                    @isset($plan->fees_amount)
                    <tr>
                        <td>Fees ({{ $plan->fees }}%)</td>
                        <td>{{ number_format((float) $plan->fees_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset

                    @isset($plan->stamps_amount)
                    <tr>
                        <td>Stamps ({{ $plan->stamps }}%)</td>
                        <td>{{ number_format((float) $plan->stamps_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset

                    @isset($plan->sales_tax_amount)
                    <tr>
                        <td>Sales Tax ({{ $plan->sales_tax }}%)</td>
                        <td>{{ number_format((float) $plan->sales_tax_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset

                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                    <tr>
                        <td>Contribution to Guarantee Fund (CBJ) ({{ $plan->cbj }}%)</td>
                        <td>{{ number_format((float) $plan->cbj_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endif

                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                    <tr>
                        <td>Sales Tax on CBJ Contribution Fund ({{ $plan->sales_tax_cbj }}%)</td>
                        <td>{{ number_format((float) $plan->sales_tax_cbj_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endif

                    @isset($plan->gross_premium_amount)
                    <tr>
                        <td>Gross Premium</td>
                        <td>{{ number_format((float) $plan->gross_premium_amount, 2) }} {{ $abbr }}</td>
                    </tr>
                    @endisset
                </table>
            </div>
        </div>

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
