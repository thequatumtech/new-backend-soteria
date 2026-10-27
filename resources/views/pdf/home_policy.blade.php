<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Insurance Policy</title>

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
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }

        .letterhead {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }

        .letterhead img {
            width: 100%;
            height: auto;
            max-height: 120px;
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

        .policy-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .policy-details td {
            padding: 10px;
            border: 1px solid #ccc;
            word-wrap: break-word;
            vertical-align: top;
        }

        .policy-details td:first-child {
            font-weight: bold;
            background-color: #e0e0e0;
        }

        /* Full-width home insurance sections */
        .home-details-grid {
            width: 100%;
            margin-bottom: 20px;
        }

        .home-layout-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .home-layout-table>tbody>tr>td {
            width: 100%;
            padding: 5px;
            vertical-align: top;
        }

        .home-layout-table>tbody>tr {
            page-break-inside: avoid;
        }

        .home-details-grid .policy-summary {
            width: 100%;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .home-details-grid h4 {
            margin: 0 0 8px;
            font-size: 13px;
        }

        .home-details-grid .policy-details {
            width: 100%;
            margin-bottom: 0;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 13px;
        }

        .home-details-grid .policy-details td {
            padding: 5px;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .home-details-grid .policy-details td:first-child {
            width: 48%;
        }

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
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 13px;
        }

        .premium-summary-table td {
            padding: 6px 8px;
            border: 1px solid #ccc;
            word-wrap: break-word;
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

        .table-scroll {
            width: 100%;
        }

        .signatures {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
            table-layout: fixed;
        }

        .signatures td {
            width: 50%;
            padding: 15px;
            border: 1px solid #ccc;
            vertical-align: top;
            text-align: center;
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
            margin: 10px auto 0;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #666;
        }

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

            .policy-details {
                font-size: 12px;
            }

            .policy-details td {
                padding: 8px;
            }

            .premium-summary-table {
                font-size: 12px;
            }

            .premium-summary-table td {
                padding: 5px 6px;
            }

            .signatures,
            .signatures tbody,
            .signatures tr {
                display: block;
                width: 100%;
            }

            .signatures td,
            .signatures.three-columns td {
                display: block;
                width: 100%;
            }

            .signature-image {
                max-width: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="policy-container">

        {{-- LETTERHEAD --}}
        @php
$letterHeadPath = null;

if (!empty($data->letterhead)) {
    $possibleLetterHeadPath = public_path(
        'insurance/' . $data->insurance_company_id . '/' . $data->letterhead
    );

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

        {{-- POLICY HEADER --}}
        <header class="policy-header">
            <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo) }}"
                alt="{{ $data->company_name ?? 'Insurance Company' }}" class="logo">
            <h1>Home Insurance Policy</h1>
        </header>

        {{-- HOME INSURANCE DETAILS: EACH SECTION IN ITS OWN ROW --}}
        <div class="home-details-grid">
            <table class="home-layout-table">
                <tbody>

                    {{-- ROW 1: POLICYHOLDER DETAILS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Policyholder Details</h4>

                                <table class="policy-details">
                                    <tbody>
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
                                            <td>
                                                {{ !empty($data->birth_date) ? date('m-d-Y', strtotime($data->birth_date)) : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td>{{ $data->gender ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Marital Status</td>
                                            <td>{{ getMaritalStatusName($data->marital_status ?? null) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Place of Residence</td>
                                            <td>{{ $data->place_of_residence ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 2: PROPERTY DETAILS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Property Details</h4>

                                <table class="policy-details">
                                    <tbody>
                                        <tr>
                                            <td>Home Type</td>
                                            <td>{{ $data->home_type ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Home Category</td>
                                            <td>{{ $data->home_category ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Home Age</td>
                                            <td>{{ $data->home_age ?? '' }}</td>
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
                                            <td>Number of Residences</td>
                                            <td>{{ $data->no_of_residence ?? '' }}</td>
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
                                            <td>Street Name</td>
                                            <td>{{ $data->street_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Building Number</td>
                                            <td>{{ $data->building_no ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 3: LOCATION DETAILS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Location Details</h4>

                                <table class="policy-details">
                                    <tbody>
                                        <tr>
                                            <td>Country</td>
                                            <td>{{ getCountryName($data->country_id ?? null) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>City</td>
                                            <td>{{ getCityName($data->city_id ?? null) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>District</td>
                                            <td>{{ getDistrictName($data->district_id ?? null) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Workplace City</td>
                                            <td>{{ getCityName($data->city_id_2 ?? null) ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 4: EMPLOYMENT DETAILS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Employment Details</h4>

                                <table class="policy-details">
                                    <tbody>
                                        <tr>
                                            <td>Company Name</td>
                                            <td>{{ $data->company_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Position</td>
                                            <td>{{ $data->position ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nature of Work</td>
                                            <td>{{ $data->work_nature ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- ROW 5: INSURANCE HISTORY AND DECLARATIONS --}}
                    <tr>
                        <td>
                            <div class="policy-summary">
                                <h4>Insurance History and Declarations</h4>

                                <table class="policy-details">
                                    <tbody>
                                        <tr>
                                            <td>Previous Policy</td>
                                            <td>{{ $data->previous_policy ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Company Declined to Issue</td>
                                            <td>{{ $data->company_declined_to_issue ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Claims / Accidents in the Past</td>
                                            <td>{{ $data->claims_accidents_past ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Protection System</td>
                                            <td>{{ $data->protection_system ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- POLICY DETAILS --}}
        <table class="policy-details">
            <tbody>
                <tr>
                    <td><strong>Policy No.</strong></td>
                    <td>{{ $data->police_no ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Plan Name</strong></td>
                    <td>{{ $data->plan_name ?? '' }}</td>
                </tr>
                <tr>
                    <td><strong>Policy Holder</strong></td>
                    <td>
                        {{ $data->first_name ?? '' }}
                        {{ $data->last_name ?? '' }}
                        {{ $data->third_name ?? '' }}
                        {{ $data->family_name ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Effective Date</strong></td>
                    <td>
                        {{ !empty($data->effective_date) ? date('m-d-Y', strtotime($data->effective_date)) : '' }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Expiry Date</strong></td>
                    <td>
                        {{ !empty($data->expiry_date) ? date('m-d-Y', strtotime($data->expiry_date)) : '' }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Payment Method</strong></td>
                    <td>Annual in advance</td>
                </tr>
            </tbody>
        </table>

        {{-- PREMIUM SUMMARY DATA --}}
        @php
$columns = [];

if (!empty($purchase->policy_plan_limit)) {
    $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;
}

if (!empty($purchase->net_premium)) {
    $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;
}

$fees_label = 'Issuance Fees' .
    (!empty($data->fees) && $data->fees > 0 ? ' (' . $data->fees . '%)' : '');

if (!empty($purchase->fees)) {
    $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;
}

$stamps_label = 'Stamps' .
    (!empty($data->stamps) && $data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');

if (!empty($purchase->stamps)) {
    $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;
}

$sales_tax_label = 'Sales Tax' .
    (!empty($data->sales_tax) && $data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');

if (!empty($purchase->sales_tax)) {
    $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;
}

$cbj_label = 'Contribution to the Guarantee Fund (CBJ)' .
    (!empty($data->cbj) && $data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');

if (!empty($purchase->cbj)) {
    $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;
}

$cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' .
    (!empty($data->sales_tax_cbj) && $data->sales_tax_cbj > 0
        ? ' (' . $data->sales_tax_cbj . '%)' : '');

if (!empty($purchase->sales_tax_cbj)) {
    $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;
}

if (!empty($purchase->gross_premium)) {
    $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;
}
        @endphp

        @if(count($columns) > 0)
            <div class="policy-summary">
                <h4>Premium Summary</h4>

                <div class="table-scroll">
                    <table class="premium-summary-table">
                        <tbody>
                            @foreach($columns as $label => $value)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- POLICY COVERS --}}
        <div class="policy-summary">
            <h4>Policy Covers</h4>

            <div class="table-scroll">
                <table class="policy-details" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <td><strong>Name of Cover</strong></td>
                            <td><strong>Limit</strong></td>
                            <td><strong>Deductible</strong></td>
                            <td><strong>Rate</strong></td>
                            <td><strong>Premium</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plan->policy_covers as $cover)
                            <tr>
                                <td>{{ $cover->cover_name }}</td>
                                <td>{{ $cover->cover_limit }}</td>
                                <td>{{ $cover->cover_deductible }}</td>
                                <td>{{ $cover->cover_rate }}</td>
                                <td>{{ number_format($cover->cover_premium, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- POLICY TEXT --}}
        <div class="policy-summary">
            {!! $data->insurance_policy_text ?? '' !!}
        </div>

        {{-- SIGNATURE AND STAMP PATHS --}}
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
    $path = public_path(
        'insurance/' . $data->insurance_company_id . '/' . $data->company_stamp
    );

    if (file_exists($path)) {
        $companyStampPath = $path;
    }
}

$authorizedSignaturePath = null;

if (!empty($data->authorized_signature)) {
    $path = public_path(
        'insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature
    );

    if (file_exists($path)) {
        $authorizedSignaturePath = $path;
    }
}
        @endphp

        {{-- SIGNATURES --}}
        @if($userSignaturePath)
            <table class="signatures three-columns">
                <tbody>
                    <tr>
                        <td>
                            <div class="signature-title">Insurer</div>
                            <div>{{ $data->company_name ?? '' }}</div>

                            @if($companyStampPath)
                                <img src="{{ $companyStampPath }}" alt="Company Stamp" class="signature-image">
                            @endif
                        </td>

                        <td>
                            <div class="signature-title">Insured</div>
                            <div>
                                {{ $data->first_name ?? '' }}
                                {{ $data->last_name ?? '' }}
                                {{ $data->third_name ?? '' }}
                                {{ $data->family_name ?? '' }}
                            </div>

                            <img src="{{ $userSignaturePath }}" alt="User Signature" class="signature-image">
                        </td>

                        <td>
                            <div class="signature-title">Authorized Signature</div>

                            @if($authorizedSignaturePath)
                                <img src="{{ $authorizedSignaturePath }}" alt="Authorized Signature" class="signature-image">
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <table class="signatures">
                <tbody>
                    <tr>
                        <td>
                            <div class="signature-title">Insurer</div>
                            <div>{{ $data->company_name ?? '' }}</div>

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
                </tbody>
            </table>
        @endif

        {{-- FOOTER --}}
        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>

    </div>
</body>

</html>

