<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Insurance Policy</title>
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .policy-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border: 1px solid #ddd;
    }

    .policy-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .policy-header .logo {
        max-width: 150px;
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
        background-color: #f0f0f0;
    }

    .policy-details td:first-child {
        font-weight: bold;
        background-color: #e0e0e0;
    }

    .policy-summary {
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .signatures {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        text-align: center;
        font-size: 14px;
        table-layout: fixed;
    }

    .signatures>tbody>tr>td {
        width: 50%;
        padding: 15px;
        border: 1px solid #ccc;
        font-weight: bold;
        background-color: #e0e0e0;
        vertical-align: top;
    }

    .signature-content {
        width: 100%;
        text-align: center;
    }

    .signature-title {
        height: 20px;
        line-height: 20px;
        margin-bottom: 10px;
    }

    .signature-image-box {
        width: 150px;
        height: 70px;
        margin: 0 auto;
        text-align: center;
    }

    .signatures img {
        width: 150px !important;
        height: 70px !important;
        max-width: 150px !important;
        max-height: 70px !important;
        min-width: 150px !important;
        min-height: 70px !important;
        display: block;
        margin: 0 auto;
    }

    .signatures .signature-image {
        width: 150px !important;
        height: 70px !important;
        max-width: 150px !important;
        max-height: 70px !important;
        min-width: 150px !important;
        min-height: 70px !important;
        display: block;
        margin: 0 auto;
    }

    .authorized-title {
        height: 20px;
        line-height: 20px;
        margin-top: 10px;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #666;
    }
</style>

<body>

    <div class="policy-container">

        <!-- HEADER -->
        <header class="policy-header">
            <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo) }}"
                alt="{{ $data->company_name }}" class="logo">
            <h1>{{ $data->plan_name }}</h1>
        </header>

        <!-- POLICY DETAILS -->
        <table class="policy-details">
            <tr>
                <td><strong>Policy No.</strong></td>
                <td>{{ $data->police_no }}</td>
            </tr>
            <tr>
                <td><strong>Policy Type</strong></td>
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
                <td><strong>Geographical Coverage</strong></td>
                <td>{{ $data->coverage_area ?? '' }}</td>
            </tr> -->
            <tr>
                <td><strong>Payment Method</strong></td>
                <td>Annual in advance</td>
            </tr>
            <!-- <tr>
                <td><strong>TPA</strong></td>
                <td>NatHealth</td>
            </tr> -->
        </table>

        @php
        $columns = [];

        if (!empty($purchase->policy_plan_limit))
        $columns['Limit'] = number_format($purchase->policy_plan_limit, 2) . ' ' . $abbr;

        if (!empty($purchase->net_premium))
        $columns['Net Premium'] = number_format($purchase->net_premium, 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . (!empty($data->fees) && $data->fees > 0 ? ' (' . $data->fees . '%)' : '');
        if (!empty($purchase->fees))
        $columns[$fees_label] = number_format($purchase->fees, 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . (!empty($data->stamps) && $data->stamps > 0 ? ' (' . $data->stamps . '%)' : '');
        if (!empty($purchase->stamps))
        $columns[$stamps_label] = number_format($purchase->stamps, 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . (!empty($data->sales_tax) && $data->sales_tax > 0 ? ' (' . $data->sales_tax . '%)' : '');
        if (!empty($purchase->sales_tax))
        $columns[$sales_tax_label] = number_format($purchase->sales_tax, 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . (!empty($data->cbj) && $data->cbj > 0 ? ' (' . $data->cbj . '%)' : '');
        if (!empty($purchase->cbj))
        $columns[$cbj_label] = number_format($purchase->cbj, 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . (!empty($data->sales_tax_cbj) && $data->sales_tax_cbj > 0 ? ' (' . $data->sales_tax_cbj . '%)' : '');
        if (!empty($purchase->sales_tax_cbj))
        $columns[$cbj_tax_label] = number_format($purchase->sales_tax_cbj, 2) . ' ' . $abbr;

        if (!empty($purchase->gross_premium))
        $columns['Gross Premium'] = number_format($purchase->gross_premium, 2) . ' ' . $abbr;

        // if(!empty($purchase->commission_amount))
        // $columns['Commission Amount'] = number_format($purchase->commission_amount, 2) . ' ' . $abbr;

        @endphp

        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <table class="policy-details">
                <tr>
                    @foreach($columns as $label => $value)
                    <td>{{ $label }}</td>
                    @endforeach
                </tr>
                <tr>
                    @foreach($columns as $label => $value)
                    <td>{{ $value }}</td>
                    @endforeach
                </tr>
            </table>
        </div>
        @endif

        <div class="policy-summary">
            <h4>Policy Covers</h4>

            <table class="policy-details">
                <tr>
                    <td><strong>Name of Cover</strong></td>
                    <td><strong>Limit</strong></td>
                    <td><strong>Deductible</strong></td>
                    <td><strong>Rate</strong></td>
                    <td><strong>Premium</strong></td>
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

        <!-- POLICY TEXT -->
        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        <!-- SIGNATURES -->
        <table class="signatures">
            <tr>
                <td>{{ $data->company_name }}</td>
                <td>Policy Holder: {{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</td>
            </tr>

            <tr>
                <td>
                    <div class="signature-content">
                        <div class="signature-title">
                            Insurer
                        </div>

                        <div class="signature-image-box">
                            <img
                                src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp) }}"
                                alt="Stamp"
                                width="150"
                                height="70"
                                class="signature-image">
                        </div>
                    </div>
                </td>

                <td>
                    <div class="signature-content">
                        <div class="signature-title">
                            Insured
                        </div>

                        <div class="signature-image-box">
                            {{-- User Signature --}}
                            @if(!empty($data->user_signature) && file_exists(public_path($data->user_signature)))
                            <img
                                src="{{ public_path($data->user_signature) }}"
                                alt="User Signature"
                                width="150"
                                height="70"
                                class="signature-image">
                            @endif
                        </div>

                        <div class="authorized-title">
                            Authorized Signature
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <footer>
            <p>This contract is prepared in two replicas for each Contracting Party.</p>
        </footer>

    </div>

</body>

</html>