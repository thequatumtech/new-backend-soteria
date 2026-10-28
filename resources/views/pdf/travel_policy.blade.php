<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Insurance Policy</title>
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
            <h1>Travel Insurance Policy</h1>
        </header>

        {{-- =====================================================
        POLICYHOLDER DETAILS
        ====================================================== --}}
        <div class="policy-summary">
            <h4>Policyholder Details</h4>
            <table class="policy-details">
                <tbody>
                    <tr>
                        <td>Family Status</td>
                        <td>
                            {{ ($data->self_family_status ?? null) == 1 ? 'Self' : (($data->self_family_status ?? null) == 2 ? 'Family' : '') }}
                        </td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td>{{ $data->first_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>{{ $data->last_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Third Name</td>
                        <td>{{ $data->third_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Family Name</td>
                        <td>{{ $data->family_name ?? '' }}</td>
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
                        <td>Residence ID Number</td>
                        <td>{{ $data->id_residence_no ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Birth Date</td>
                        <td>{{ !empty($data->birth_date) ? date('m-d-Y', strtotime($data->birth_date)) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>{{ $data->gender ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Marital Status</td>
                        <td>{{ $data->marital_status ?? null }}</td>
                    </tr>
                    <tr>
                        <td>Place of Residence</td>
                        <td>{{ $data->place_residence ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- =====================================================
        TRAVEL DETAILS
        ====================================================== --}}
        <div class="policy-summary">
            <h4>Travel Details</h4>
            <table class="policy-details">
                <tbody>
                    <tr>
                        <td>Departure Country</td>
                        <td>{{ getCountryName($data->departure_from_country_id ?? null) }}</td>
                    </tr>
                    <tr>
                        <td>Destination Country</td>
                        <td>{{ getCountryName($data->destination_country_id ?? null) }}</td>
                    </tr>
                    <tr>
                        <td>Additional Destination Country</td>
                        <td>{{ getCountryName($data->additional_destination_country_id ?? null) }}</td>
                    </tr>
                    <tr>
                        <td>Multiple Destinations</td>
                        <td>{{ $data->multiple_destination ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Geographical Area</td>
                        <td>{{ $data->geographical_area_name ?? $data->geographical_area_id ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Geographical Countries</td>
                        <td>
                            @php
$countries = $data->geographical_countries ?? [];
if (is_string($countries)) {
    $countries = json_decode($countries, true) ?? [];
}
                            @endphp

                            {{ is_array($countries) ? implode(', ', $countries) : $countries }}
                        </td>
                    </tr>
                    <tr>
                        <td>Effective Date</td>
                        <td>{{ !empty($data->effective_date) ? date('m-d-Y', strtotime($data->effective_date)) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Travel Days</td>
                        <td>{{ $data->travel_days ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Expiry Date</td>
                        <td>{{ !empty($data->expiry_date) ? date('m-d-Y', strtotime($data->expiry_date)) : '' }}</td>
                    </tr>
                    <tr>
                        <td>Insurance Limit</td>
                        <td>
                            {{ isset($data->insurance_limit) ? $data->insurance_limit : (isset($plan->limit) ? number_format((float) $plan->limit, 2) . ' ' . ($abbr ?? 'JOD') : '') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Renewal</td>
                        <td>{{ !empty($data->renew) ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td>Previous Policy ID</td>
                        <td>{{ $data->old_policy_id_for_renew ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- =====================================================
        TRAVEL DECLARATIONS
        ====================================================== --}}
        <div class="policy-summary">
            <h4>Travel Declarations</h4>
            <table class="policy-details">
                <tbody>
                    <tr>
                        <td>Dangerous Activities</td>
                        <td>{{ $data->dangerous_activities ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Passport Document</td>
                        <td>{{ $data->passport_document ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- =====================================================
        FAMILY MEMBERS
        ====================================================== --}}
        @if(isset($data->members) && count($data->members) > 0)
            <div class="policy-summary">
                <h4>Insured Family Members</h4>
                <div class="table-scroll">
                    <table class="policy-details">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Relationship</td>
                                <td>Nationality</td>
                                <td>Birth Date</td>
                                <td>Gender</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->members as $member)
                                <tr>
                                    <td>
                                        {{ $member->first_name ?? '' }}
                                        {{ $member->last_name ?? '' }}
                                        {{ $member->third_name ?? '' }}
                                        {{ $member->family_name ?? '' }}
                                    </td>
                                    <td>{{ $member->relation ?? '' }}</td>
                                    <td>{{ $member->nationality ?? '' }}</td>
                                    <td>
                                        {{ !empty($member->birth_date) ? date('m-d-Y', strtotime($member->birth_date)) : '' }}
                                    </td>
                                    <td>{{ $member->gender ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


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
                <td>{{ $data->effective_date ? date('m-d-Y', strtotime($data->effective_date)) : '' }}</td>
            </tr>
            <tr>
                <td><strong>Expiry Date</strong></td>
                <td>{{ $data->expiry_date ? date('m-d-Y', strtotime($data->expiry_date)) : '' }}</td>
            </tr>
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
                            <td>{{ number_format((float) $plan->limit, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset

                    @isset($plan->net_premium_amount)
                        <tr>
                            <td>Net Premium</td>
                            <td>{{ number_format((float) $plan->net_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset

                    @isset($plan->fees_amount)
                        <tr>
                            <td>Fees ({{ $plan->fees }}%)</td>
                            <td>{{ number_format((float) $plan->fees_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset

                    @isset($plan->stamps_amount)
                        <tr>
                            <td>Stamps ({{ $plan->stamps }}%)</td>
                            <td>{{ number_format((float) $plan->stamps_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset

                    @isset($plan->sales_tax_amount)
                        <tr>
                            <td>Sales Tax ({{ $plan->sales_tax }}%)</td>
                            <td>{{ number_format((float) $plan->sales_tax_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset

                    @if(isset($plan->cbj_amount) && $plan->cbj_amount > 0)
                        <tr>
                            <td>Contribution to Guarantee Fund (CBJ) ({{ $plan->cbj }}%)</td>
                            <td>{{ number_format((float) $plan->cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endif

                    @if(isset($plan->sales_tax_cbj_amount) && $plan->sales_tax_cbj_amount > 0)
                        <tr>
                            <td>Sales Tax on CBJ Contribution Fund ({{ $plan->sales_tax_cbj }}%)</td>
                            <td>{{ number_format((float) $plan->sales_tax_cbj_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endif

                    @isset($plan->gross_premium_amount)
                        <tr>
                            <td>Gross Premium</td>
                            <td>{{ number_format((float) $plan->gross_premium_amount, 2) }} {{ $abbr ?? 'JOD' }}</td>
                        </tr>
                    @endisset
                </table>
            </div>
        </div>

        <!-- POLICY COVERS -->
        <div class="policy-summary">
            <h4>Policy Covers</h4>
            <div class="table-scroll">
                <table class="policy-details" style="display: table; width: 100%; min-width: 320px;">
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
