<table>
    <thead>
        <tr>
            <th><b>{{__('messages.reports.client')}}</b></th>
            <th><b>{{__('messages.sidebar_titles.age')}}</b></th>
            <th><b>{{__('messages.clients.position')}}</b></th>
            <th><b>{{__('messages.sidebar_titles.district')}}</b></th>
            <th><b>{{__('messages.insurance_company.city')}}</b></th>
            <th><b>{{__('messages.reports.policy_type')}}</b></th>
            <th><b>{{__('messages.reports.policy_plan')}}</b></th>
            <th><b>{{__('messages.reports.clients_gender')}}</b></th>
            <th><b>{{__('messages.clients.marital_status')}}</b></th>
            <th><b>{{__('messages.claims.effective_date')}}</b></th>
            <th><b>{{__('messages.claims.expiry_date')}}</b></th>
            <th><b>{{__('messages.reports.gross_premium')}}</b></th>
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
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->age }}</td>
                        <td>{{ $policy->position }}</td>
                        <td>{{ $policy->district }}</td>
                        <td>{{ $policy->city }}</td>
                        <td>{{ $policy->policy_type }}</td>
                        <td>{{ $policy->plan_name }}</td>
                        <td>{{ $policy->gender }}</td>
                        <td>{{ $policy->marital_status }}</td>
                        <td>{{ $policy->start_date }}</td>
                        <td>{{ $policy->expiry_date }}</td>
                        <td>{{ $policy->gross_premium }}</td>
                    </tr>
                @endforeach
                <!-- Totals Row -->
                <tr>
                    <td colspan="11"><strong>TOTAL</strong></td>
                    <td>{{ $data['totals']['total_gross_premium'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>