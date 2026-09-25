<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Claim Submitted Successfully</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <p>
        Dear <strong>{{ $claim->client?->full_name ?? '' }}</strong>,
    </p>

    <p>
        We would like to inform you that your insurance claim has been
        successfully submitted and received by our system.
    </p>

    <h2>Claim Details</h2>

    <p>
        <strong>Claim Number:</strong>
        {{ $claim->claim_no }}
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
        <strong>Claim Date:</strong>
        {{ \Carbon\Carbon::parse($claim->created_at)->format('d/m/Y') }}
    </p>

    <p>
        <strong>Claim Status:</strong>
        {{ $claim->status }}
    </p>

    <p>
        <strong>Claim Details / Comment:</strong>
    </p>

    <div>
        {!! $claim->claim_note ?: '-' !!}
    </div>

    <p>
        Our team will review your claim and process it according to the
        applicable policy terms and procedures. You will be notified when
        there is an update regarding your claim.
    </p>

    <p>
        If you have any questions or need to provide additional information,
        please contact our support team.
    </p>

    <p>
        Regards,<br>
        <strong>{{ env('MAIL_FROM_NAME') }}</strong>
    </p>

</body>
</html>