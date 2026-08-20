<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sold Policy Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #004d99;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 10px 0 5px 0;
            color: #004d99;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }
        .filter-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #004d99;
            font-size: 11px;
        }
        .filter-info strong {
            color: #004d99;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        table thead {
            background-color: #004d99;
            color: white;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #004d99;
        }
        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        .total-row {
            background-color: #e6f2ff;
            font-weight: bold;
            color: #004d99;
        }
        .group-title {
            background-color: #d9e6f7;
            font-weight: bold;
            color: #004d99;
            padding: 10px 8px;
            margin-top: 15px;
            margin-bottom: 5px;
            border-left: 4px solid #004d99;
            page-break-inside: avoid;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sold Policy Report</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <p>Date Range: From {{ $issue_date ?? '--' }} To {{ $expiry_date ?? '--' }}</p>
    </div>

    @if($policies && count($policies) > 0)
        @foreach($policies as $company => $data)
            <div class="group-title">{{ $company }}</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Policy No</th>
                        <th>Client Name</th>
                        <th>Insurance Company</th>
                        <th>Policy Type</th>
                        <th>Agent</th>
                        <th>Supervisor</th>
                        <th>Effective Date</th>
                        <th>Expiry Date</th>
                        <th>Position</th>
                        <th>Policy Limit</th>
                        <th>Net Premium</th>
                        <th>Gross Premium</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['policies'] as $policy)
                        <tr>
                            <td>{{ $policy->policy_no ?? '--' }}</td>
                            <td>{{ $policy->client_name ?? '--' }}</td>
                            <td>{{ $policy->company_name ?? '--' }}</td>
                            <td>{{ $policy->policy_type ?? '--' }}</td>
                            <td>{{ $policy->agent_name ?? '--' }}</td>
                            <td>{{ $policy->supervisor_name ?? '--' }}</td>
                            <td>{{ $policy->inception_date ?? '--' }}</td>
                            <td>{{ $policy->expiry_date ?? '--' }}</td>
                            <td>{{ $policy->occupations_name ?? '--' }}</td>
                            <td>{{ $policy->policy_plan_limit ?? '--' }}</td>
                            <td>{{ number_format($policy->net_premium ?? 0, 2) }}</td>
                            <td>{{ number_format($policy->gross_premium ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    <!-- Totals Row -->
                    <tr class="total-row">
                        <td colspan="10" style="text-align: right;">TOTAL</td>
                        <td>{{ number_format($data['totals']['total_net_premium'] ?? 0, 2) }}</td>
                        <td>{{ number_format($data['totals']['total_gross_premium'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @else
        <div style="text-align: center; padding: 20px; color: #999;">
            No records found for the selected criteria.
        </div>
    @endif

    <div class="footer">
        <p>This is a system-generated report. For more information, please contact the administration.</p>
    </div>
</body>
</html>
