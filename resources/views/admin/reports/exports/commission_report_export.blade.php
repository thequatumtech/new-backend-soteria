<table>
    <thead>
        <tr>
            <th><b>{{__('messages.reports.client')}}</b></th>
            <th><b>{{__('messages.reports.policy_type')}}</b></th>
            <th><b>{{__('messages.reports.policy_plan')}}</b></th>
            <th><b>{{__('messages.agents.agent_commission')}}</b></th>
            <th><b>{{__('messages.plans.net_premium')}}</b></th>
            <th><b>{{__('messages.reports.total_commission')}}</b></th>
            <th><b>{{__('messages.insurance_company.insurance_company')}}</b></th>
        </tr>
    </thead>
    <tbody>
        @if($policies)
            @foreach ($policies as $company => $data)
                <tr>
                    <td colspan="7"><b>{{ $company }}</b></td>
                </tr>
                @foreach ($data['policies'] as $policy)
                    <tr>
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->policy_type }}</td>
                        <td>{{ $policy->plan_name }}</td>
                        <td>{{ $policy->commission_percentage }}</td>
                        <td>{{ $policy->net_premium }}</td>
                        <td>{{ $policy->commission_amount }}</td>
                        <td>{{ $policy->company_name }}</td>
                    </tr>
                @endforeach
                <!-- Totals Row (if you want to add totals) -->
                @if(isset($data['totals']))
                    <tr>
                        <td colspan="4"><strong>TOTAL</strong></td>
                        <td>{{ $data['totals']['total_net_premium'] ?? '' }}</td>
                        <td>{{ $data['totals']['total_commission'] ?? '' }}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>