@extends('layouts.mainlayout')
@section('style')
<style>
    input {
        border: none;
        font-family: 'Nunito';
        width: 100%;
        height: 2.5rem;
        font-weight: 600;
    }

    input:focus-visible {
        outline: none;
    }

    .input-group {
        font-weight: 600;
        font-family: 'Nunito';
    }

    .shadow1 {
        box-shadow: 4px 4px 13px -3px #00000040;
    }

    /* Style for modal content */
    .view-modal-content {
        background-color: #fefefe;
        /*margin: 5% auto;*/
        margin-left: 5%;
        /*padding: 20px;*/
        padding-top: 20px;
        /*border: 1px solid #888;*/
        width: 80%;
    }

    /* Override default error styles */
    .error {
        color: inherit;
        /* Set the color to inherit */
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.reports.supervisor_report')}}</div>
            @include('admin.reports.partials.report-download-controls', [
            'pdfRoute' => route('reports.download', ['report' => 'supervisor_report', 'format' => 'pdf']),
            'excelRoute' => route('reports.download', ['report' => 'supervisor_report', 'format' => 'excel']),
            'filePrefix' => 'Supervisor_Report',
            ])
        </div>
        <!-- Form to be toggled -->
        <form id="reportForm" action="" method="GET" class="d-none">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <!-- <label for="grouptype">Group Report By</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="insurance_company" {{ request('grouptype')=='insurance_company' ? 'selected' : ''}}>Insurance Company</option>
                                    <option value="supervisor_name" {{ request('grouptype')=='supervisor_name' ? 'selected' : ''}}>Supervisor</option>
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>Policy Type</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>Agents</option>
                                </select> -->
                                <label for="grouptype">{{__('messages.supervisor_report.group_report_by')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="insurance_company" {{ request('grouptype')=='insurance_company' ? 'selected' : ''}}>{{__('messages.supervisor_report.grouptype_insurance_company')}}</option>
                                    <option value="supervisor_name" {{ request('grouptype')=='supervisor_name' ? 'selected' : ''}}>{{__('messages.supervisor_report.grouptype_supervisor')}}</option>
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>{{__('messages.supervisor_report.grouptype_policy_type')}}</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>{{__('messages.supervisor_report.grouptype_agents')}}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                             <label for="insurance_company">{{__('messages.supervisor_report.insurance_company')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="insurance_company" id="insurance_company">
                                <option value="">{{__('messages.supervisor_report.select_company')}}</option>
                                    @foreach ($insuranceCompanies as $company)
                                    <option value="{{ $company->id }}" {{ request('insurance_company')==$company->id ? 'selected' : ''}}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="issue_date">{{__('messages.supervisor_report.from_date')}}</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="issue_date" id="issue_date" value="{{request('issue_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="expiry_date">{{__('messages.supervisor_report.to_date')}}</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{request('expiry_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                    <label for="policy_type">{{__('messages.supervisor_report.policy_type')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="policy_type" id="policy_type">
                                <option value="">{{__('messages.supervisor_report.select_policy_type')}}</option>

                                    @foreach ($policyTypes as $type)
                                    <option value="{{ $type['id'] }}" {{ request('policy_type')==$type['id'] ? 'selected' : ''}}>{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                             <label for="supervisor_name">{{__('messages.supervisor_report.supervisor_name')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="supervisor_name" id="supervisor_name">
                                        <option value="">{{__('messages.supervisor_report.select_supervisor_name')}}</option>
                                    @foreach ($supervisors as $supervisor)
                                    <option value="{{ $supervisor['id'] }}" {{ request('supervisor_name')==$supervisor['id'] ? 'selected' : ''}}>{{ $supervisor['first_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="agent_name">{{__('messages.supervisor_report.agent_name')}}</label>

                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="agent_name" id="agent_name">
                                       <option value="">{{__('messages.supervisor_report.select_agent')}}</option>

                                    @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_name')==$agent->id ? 'selected' : ''}}>{{ $agent->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <button type="submit" class="btn rounded-1 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.supervisor_report.generate_report')}}</button>
                                <!-- <button type="submit" class="btn rounded-1 text-white opacity-50 p-2" style="background-color: #EF7C00;">Generate Report</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="m-2 text-center">
            <img class="p-3" src="{{asset('img/DashboardSotariaLogo.png')}}" alt="">
            <h4>Policies by
                @php
                switch(request('grouptype'))
                {
                case 'supervisor_name':
                echo "Supervisor Name";
                break;
                case 'policy_type':
                echo "Policy Type";
                break;
                case 'agent_name':
                echo "Agent Name";
                break;
                case 'insurance_company':
                echo "Insurance Company";
                break;
                default:
                echo "Insurance Company";
                break;
                }
                @endphp
            </h4>
            <p>From: {{ request('issue_date') ?? '--' }} To: {{ request('expiry_date') ?? '--' }}</p>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            @if($policies)
            @foreach ($policies as $company => $data)
            <h3>{{ $company }}</h3>
            <table class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th class="no-search">{{__('messages.reports.client')}}</th>
                        <th>{{__('messages.reports.policy_type')}}</th>
                        <th class="no-search">{{__('messages.reports.agent_name')}}</th>
                        <th class="no-search">{{__('messages.reports.supervisor_name')}}</th>
                        <th class="no-search">{{__('messages.agents.agent_commission')}}</th>
                        <th class="no-order no-search">{{__('messages.reports.supervisor_override')}}</th>
                        <th class="no-order no-search">{{__('messages.reports.net_premium')}}</th>
                        <th class="no-order no-search">{{__('messages.reports.total_commission')}}</th>
                        <th>{{__('messages.clients.company_name')}}</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
            @endforeach
            @else
            <h4 class="text-center">No Record Found</h4>
            @endif
        </div>
    </div>
    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
        <input type="hidden" id="selected_client_id">
    </div>
    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
        <input type="hidden" id="selected_client_names">
    </div>
</main>
@endsection

@section('script')
<script>
    // Get the form and the toggle button
    const toggleFormBtn = document.getElementById('toggleForm');
    const reportForm = document.getElementById('reportForm');

    // Add event listener to the toggle button
    toggleFormBtn.addEventListener('click', function() {
        // Toggle the 'd-none' class on the form
        reportForm.classList.toggle('d-none');
    });
</script>
<script src="{{asset('js/client.js?v=1.2')}}">
</script>
@include('admin.reports.partials.report-download-js')
@endsection