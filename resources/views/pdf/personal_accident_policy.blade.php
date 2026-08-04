<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Accident Insurance Policy</title>
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
        font-size: 20px;
        margin: 0;
        font-weight: bold;
    }

    .policy-details,
    .signatures {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .policy-details td,
    .signatures td {
        padding: 10px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .policy-details td:first-child {
        font-weight: bold;
        background-color: #e0e0e0;
    }

    .summary-table td {
        background-color: #f0f0f0;
        text-align: center;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #666;
    }
</style>

<body>

    <div class="policy-container">

        <header class="policy-header">
            <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->logo) }}" alt="Arabia Insurance Jordan" class="logo">
            <h1>Personal Accident Insurance Contract</h1>
        </header>

        <table class="policy-details">
            <tr>
                <td>Policy No.</td>
                <td>{{ $data->police_no }}</td>
            </tr>
            <tr>
                <td>Policy Type</td>
                <td>{{ $data->plan_name }}</td>
            </tr>
            <tr>
                <td>Policy Holder</td>
                <td>{{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</td>
            </tr>
            <tr>
                <td>Effective Date</td>
                <td>{{ date('m-d-Y', strtotime($data->inception_date)) }}</td>
            </tr>
            <tr>
                <td>Expiry Date</td>
                <td>{{ date('m-d-Y', strtotime($data->expiry_date)) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>Annual in Advance</td>
            </tr>
            <!-- <tr>
                <td>TPA</td>
                <td>NatHealth</td>
            </tr> -->
        </table>

        @php
        $abbr = $abbr ?? 'JOD';

        $feesRate = $plan->fees ?? 0;
        $stampsRate = $plan->stamps ?? 0;
        $salesTaxRate = $plan->sales_tax ?? 0;
        $cbjRate = $plan->cbj ?? 0;
        $cbjTaxRate = $plan->sales_tax_cbj ?? 0;

        $columns = [];

        if(!empty($plan->limit))
        $columns['Limit'] = number_format((float)$plan->limit, 2) . ' ' . $abbr;

        if(!empty($purchase['net_premium']))
        $columns['Net Premium'] = number_format((float)$purchase['net_premium'], 2) . ' ' . $abbr;

        $fees_label = 'Issuance Fees' . ($feesRate > 0 ? ' (' . $feesRate . '%)' : '');
        if(!empty($purchase['fees']))
        $columns[$fees_label] = number_format((float)$purchase['fees'], 2) . ' ' . $abbr;

        $stamps_label = 'Stamps' . ($stampsRate > 0 ? ' (' . $stampsRate . '%)' : '');
        if(!empty($purchase['stamps']))
        $columns[$stamps_label] = number_format((float)$purchase['stamps'], 2) . ' ' . $abbr;

        $sales_tax_label = 'Sales Tax' . ($salesTaxRate > 0 ? ' (' . $salesTaxRate . '%)' : '');
        if(!empty($purchase['sales_tax']))
        $columns[$sales_tax_label] = number_format((float)$purchase['sales_tax'], 2) . ' ' . $abbr;

        $cbj_label = 'Contribution to the Guarantee Fund (CBJ)' . ($cbjRate > 0 ? ' (' . $cbjRate . '%)' : '');
        if(!empty($purchase['cbj']))
        $columns[$cbj_label] = number_format((float)$purchase['cbj'], 2) . ' ' . $abbr;

        $cbj_tax_label = 'Sales Tax on CBJ Contribution Fund' . ($cbjTaxRate > 0 ? ' (' . $cbjTaxRate . '%)' : '');
        if(!empty($purchase['sales_tax_cbj']))
        $columns[$cbj_tax_label] = number_format((float)$purchase['sales_tax_cbj'], 2) . ' ' . $abbr;

        if(!empty($purchase['gross_premium']))
        $columns['Gross Premium'] = number_format((float)$purchase['gross_premium'], 2) . ' ' . $abbr;
        @endphp

        @if(count($columns) > 0)
        <div class="policy-summary">
            <h4>Premium Summary</h4>
            <table class="policy-details summary-table">
                <tr>
                    @foreach($columns as $label => $value)
                    <td><strong>{{ $label }}</strong></td>
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
        @if(!empty($plan->covers) && count($plan->covers) > 0)

        <h4>Policy Covers</h4>

        <table class="policy-details">
            <tr>
                <td><strong>Name of Cover</strong></td>
                <td><strong>Limit</strong></td>
                <td><strong>Deductible</strong></td>
            </tr>

            @foreach($plan->covers as $cover)
            <tr>
                <td>{{ $cover->cover_name }}</td>

                <td>
                    {{ $cover->cover_limit }}
                </td>

                <td>
                    {{ $cover->cover_deductible }}
                </td>
            </tr>
            @endforeach
        </table>

        @endif
        <div class="policy-summary">
            {!! $data->insurance_policy_text !!}
        </div>

        <table class="signatures">
            <tr>
                <td>{{ $data->company_name }}</td>
                <td>Policy Holder / {{ $data->first_name }} {{ $data->last_name }} {{ $data->third_name }} {{ $data->family_name }}</td>
            </tr>

            <tr>
                <td>
                    Insurer <br>
                    <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->company_stamp) }}" alt="" class="logo">
                </td>
                <td>
                    Insured <br>
                    <img src="{{ public_path('insurance/' . $data->insurance_company_id . '/' . $data->authorized_signature) }}" alt="" class="logo">
                </td>
            </tr>
        </table>

        <footer>
            <p>This contract is prepared in two copies for each contracting party.</p>
        </footer>

    </div>

</body>

</html>