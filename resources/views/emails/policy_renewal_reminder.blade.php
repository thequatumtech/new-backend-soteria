<h2>Renewal Reminder - {{ $daysBefore }} Days Left</h2>
<p>Hi {{ $policy->client->full_name }},</p>
<p>Your policy #{{ $policy->policy_no }} will expire on {{ \Carbon\Carbon::parse($policy->expiry_date)->format('d M Y') }}.</p>
<p>Please renew it before expiry.</p>
<p>Thank you for choosing us for your insurance needs.</p>
<p>Best regards,</p>