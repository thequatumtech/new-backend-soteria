<table>
    <thead>
        <tr>
            <th><b>{{__('messages.reports.client')}}</b></th>
            <th><b>{{__('messages.reports.policy_type')}}</b></th>
            <th><b>{{__('messages.reports.agent_name')}}</b></th>
            <th><b>{{__('messages.reports.supervisor_name')}}</b></th>
            <th><b>{{__('messages.agents.agent_commission')}}</b></th>
            <th><b>{{__('messages.reports.supervisor_override')}}</b></th>
            <th><b>{{__('messages.reports.net_premium')}}</b></th>
            <th><b>{{__('messages.reports.total_commission')}}</b></th>
            <th><b>{{__('messages.clients.company_name')}}</b></th>
        </tr>
    </thead>
    <tbody>
        @if($policies)
            @foreach ($policies as $company => $data)
                <tr>
                    <td colspan="9"><b>{{ $company }}</b></td>
                </tr>
                @foreach ($data['policies'] as $policy)
                    @php
                        $sv_commission_amount=$policy->net_premium*($policy->agent_commission_amount/100);
                        $sv_total_commission_amount=+$sv_commission_amount;
                    @endphp
                    <tr>
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->policy_type }}</td>
                        <td>{{ $policy->agent_name }}</td>
                        <td>{{ $policy->supervisor_name }}</td>
                        <td>{{ $policy->agent_commission_amount }} %</td>
                        <td>{{ $policy->supervisor_commission_amount }} %</td>
                        <td>{{ $policy->net_premium }}</td>
                        <td>{{ $sv_commission_amount }}</td>
                        <td>{{ $policy->company_name }}</td>
                    </tr>
                @endforeach
                <!-- Totals Row -->
                <tr>
                    <td colspan="6"><strong>TOTAL</strong></td>
                    <td>{{ $data['totals']['total_net_premium'] }}</td>
                    <td>{{ $sv_total_commission_amount }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
