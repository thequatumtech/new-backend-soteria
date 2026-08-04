<table>
    <thead>
    <tr>
        <th><b>{{__('messages.reports.policy_no')}}</b></th>
        <th><b>{{__('messages.reports.client')}}</b></th>
        <th><b>{{__('messages.claims.insurance_company')}}</b></th>
        <th><b>{{__('messages.claims.policy_type')}}</b></th>
        <th><b>{{__('messages.reports.agent')}}</b></th>
        <th><b>{{__('messages.agents.supervisor')}}</b></th>
        <th><b>{{__('messages.discount_coupons.effective_date')}}</b></th>
        <th><b>{{__('messages.discount_coupons.expiry_date')}}</b></th>
        <th><b>{{__('messages.clients.position')}}</b></th>
        <th><b>{{__('messages.reports.policy_limit')}}</b></th>
        <th><b>{{__('messages.plans.net_premium')}}</b></th>
        <th><b>{{__('messages.plans.gross_premium')}}</b></th>
    </tr>
    </thead>
    <tbody>
    @if($policies)
        @foreach ($policies as $company => $data)
            <tr>
                <td colspan="12"><b>{{ $company }}</b></td>
            </tr>
            @foreach ($data['policies'] as $policy)
                <tr>
                    <td>{{ $policy->policy_no }}</td>
                    <td>{{ $policy->client_name }}</td>
                    <td>{{ $policy->company_name }}</td>
                    <td>{{ $policy->policy_type }}</td>
                    <td>{{ $policy->agent_name }}</td>
                    <td>{{ $policy->supervisor_name }}</td>
                    <td>{{ $policy->inception_date }}</td>
                    <td>{{ $policy->expiry_date }}</td>
                    <td>{{ $policy->occupations_name }}</td>
                    <td>{{ $policy->policy_plan_limit }}</td>
                    <td>{{ $policy->net_premium }}</td>
                    <td>{{ $policy->gross_premium }}</td>
                </tr>
            @endforeach
            <!-- Totals Row -->
            <tr>
                <td colspan="10"><strong>TOTAL</strong></td>
                <td>{{ $data['totals']['total_net_premium'] }}</td>
                <td>{{ $data['totals']['total_gross_premium'] }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
