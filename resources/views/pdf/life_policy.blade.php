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

        @if($letterHeadPath)
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
            <tr><td>First Name</td><td>{{ $data->first_name ?? '' }}</td></tr>
            <tr><td>Last Name</td><td>{{ $data->last_name ?? '' }}</td></tr>
            <tr><td>Third Name</td><td>{{ $data->third_name ?? '' }}</td></tr>
            <tr><td>Family Name</td><td>{{ $data->family_name ?? '' }}</td></tr>
            <tr><td>Nationality</td><td>{{ $data->nationality ?? '' }}</td></tr>
            <tr><td>Nationality Number</td><td>{{ $data->nationality_no ?? '' }}</td></tr>
            <tr><td>Residence ID Number</td><td>{{ $data->id_residence_no ?? '' }}</td></tr>
            <tr><td>Birth Date</td><td>{{ !empty($data->birth_date) ? date('m-d-Y', strtotime($data->birth_date)) : '' }}</td></tr>
            <tr><td>Gender</td><td>{{ $data->gender ?? '' }}</td></tr>
            <tr><td>Marital Status</td><td>{{ getMaritalStatusName($data->marital_status ?? null) }}</td></tr>
            <tr><td>Place of Residence</td><td>{{ $data->place_residence ?? '' }}</td></tr>
            <tr><td>Occupation / Work</td><td>{{ $data->occupancy_work ?? '' }}</td></tr>
            <tr><td>American Nationality Status</td><td>{{ $data->american_notionality_status ?? '' }}</td></tr>
            <tr><td>Beneficiary First Name</td><td>{{ $data->beneficiary_first_name ?? '' }}</td></tr>
            <tr><td>Beneficiary Last Name</td><td>{{ $data->beneficiary_last_name ?? '' }}</td></tr>
            <tr><td>Beneficiary Third Name</td><td>{{ $data->beneficiary_third_name ?? '' }}</td></tr>
        </tbody>
    </table>
</div>

{{-- RESIDENTIAL ADDRESS --}}
<div class="policy-summary">
    <h4>Residential Address</h4>
    <table class="policy-details">
        <tbody>
            <tr><td>Country</td><td>{{ getCountryName($data->country_id ?? null) }}</td></tr>
            <tr><td>City</td><td>{{ getCityName($data->city_id ?? null) }}</td></tr>
            <tr><td>District</td><td>{{ getDistrictName($data->district_id ?? null) }}</td></tr>
            <tr><td>Street Name</td><td>{{ $data->street_name ?? '' }}</td></tr>
            <tr><td>Building Number</td><td>{{ $data->building_no ?? '' }}</td></tr>
        </tbody>
    </table>
</div>

{{-- EMPLOYMENT DETAILS --}}
<div class="policy-summary">
    <h4>Employment Details</h4>
    <table class="policy-details">
        <tbody>
            <tr><td>Employee Status</td><td>{{ $data->employee_status ?? '' }}</td></tr>
            <tr><td>Company Name</td><td>{{ $data->company_name ?? '' }}</td></tr>
            <tr><td>Position</td><td>{{ $data->position ?? '' }}</td></tr>
            <tr><td>Nature of Work</td><td>{{ $data->work_nature ?? '' }}</td></tr>
            <tr><td>Company City</td><td>{{ getCityName($data->employee_city_id ?? null) }}</td></tr>
            <tr><td>Company District</td><td>{{ getDistrictName($data->employee_district_id ?? null) }}</td></tr>
            <tr><td>Company Street</td><td>{{ $data->employee_street_name ?? '' }}</td></tr>
            <tr><td>Company Building Number</td><td>{{ $data->employee_building_no ?? '' }}</td></tr>
            <tr><td>Company Contact</td><td>{{ $data->company_contact ?? '' }}</td></tr>
        </tbody>
    </table>
</div>

{{-- MEDICAL HISTORY --}}
<div class="policy-summary">
    <h4>Medical History and Declarations</h4>
    <table class="policy-details">
        <tbody>
            <tr><td>Height</td><td>{{ $data->height ?? '' }}</td></tr>
            <tr><td>Weight</td><td>{{ $data->wight ?? '' }}</td></tr>
            <tr><td>Chronic Diseases</td><td>{{ $data->chronic_diseases_id ?? '' }}</td></tr>
            <tr><td>Previous Operation</td><td>{{ $data->previous_operation ?? '' }}</td></tr>
            <tr><td>Operation Details</td><td>{{ $data->operation_details ?? '' }}</td></tr>
            <tr><td>Company Declined Policy</td><td>{{ $data->company_declined_policy ?? '' }}</td></tr>
            <tr><td>Declined Policy Details</td><td>{{ $data->declined_policy_details ?? '' }}</td></tr>
            <tr><td>Existing Life Insurance</td><td>{{ $data->exiting_life_insur ?? '' }}</td></tr>
            <tr><td>Existing Life Insurance Details</td><td>{{ $data->exiting_life_insur_details ?? '' }}</td></tr>
        </tbody>
    </table>
</div>

{{-- BENEFICIARY DETAILS --}}
<div class="policy-summary">
    <h4>Beneficiary Details</h4>
    <table class="policy-details">
        <tbody>
            <tr><td>First Name</td><td>{{ $data->beneficiary_first_name ?? '' }}</td></tr>
            <tr><td>Last Name</td><td>{{ $data->beneficiary_last_name ?? '' }}</td></tr>
            <tr><td>Third Name</td><td>{{ $data->beneficiary_third_name ?? '' }}</td></tr>
        </tbody>
    </table>
</div>

{{-- LIFE INSURANCE COVERAGE --}}
<div class="policy-summary">
    <h4>Life Insurance Coverage Details</h4>
    <table class="policy-details">
        <tbody>
            <tr><td>Insurance Amount</td><td>{{ $data->insurance_amount ?? '' }}</td></tr>
            <tr><td>Effective Date</td><td>{{ !empty($data->effective_date) ? date('m-d-Y', strtotime($data->effective_date)) : '' }}</td></tr>
            <tr><td>Insurance Period (Years)</td><td>{{ $data->insurance_period ?? '' }}</td></tr>
            <tr><td>Inception Date</td><td>{{ !empty($data->inception_date) ? date('m-d-Y', strtotime($data->inception_date)) : '' }}</td></tr>
            <tr><td>Expiry Date</td><td>{{ !empty($data->expiry_date) ? date('m-d-Y', strtotime($data->expiry_date)) : '' }}</td></tr>
            <tr><td>Renewal</td><td>{{ !empty($data->renew) ? 'Yes' : 'No' }}</td></tr>
            <tr><td>Previous Policy ID</td><td>{{ $data->old_policy_id_for_renew ?? '' }}</td></tr>
        </tbody>
    </table>
</div>




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
