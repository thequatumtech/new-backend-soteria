<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Claim Notification</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <p>
        Dear <strong>{{ $claim->insurance_company?->company_name ?? '' }}</strong> Team,
    </p>

    <p>
        A new insurance claim has been submitted and requires your attention.
    </p>

    <p>Please find the claim details below:</p>

    <h2>Claim Details</h2>

    <p>
        <strong>Claim Number:</strong>
        {{ $claim->claim_no }}
    </p>

    <p>
        <strong>Client Name:</strong>
        {{ $claim->client?->full_name ?? '' }}
    </p>

    <p>
        <strong>Policy Type:</strong>
        {{ __('messages.policy_types.' . $claim->policy_type) }}
    </p>

    <p>
        <strong>Insurance Company:</strong>
        {{ $claim->insurance_company?->company_name ?? '' }}
    </p>

    <p>
        <strong>Effective Date:</strong>
        {{ \Carbon\Carbon::parse($claim->effective_date)->format('d/m/Y') }}
    </p>

    <p>
        <strong>Expiry Date:</strong>
        {{ \Carbon\Carbon::parse($claim->expiry_date)->format('d/m/Y') }}
    </p>

    <p>
        <strong>Insurance Limit:</strong>
        {{ $purchasepolicy->policy_plan_limit }}
    </p>

    <p>
        <strong>Policy Premium:</strong>
        {{ $purchasepolicy->net_premium }}
    </p>

    <p>
        <strong>Number of Claims:</strong>
        {{ $no_of_claims }}
    </p>

    <p>
        <strong>Claim Status:</strong>
        {{ $claim->status }}
    </p>

    <p>
        <strong>Client/User Comment:</strong>
    </p>

    <div>
        {!! $claim->claim_note ?: '-' !!}
    </div>

</body>
</html>