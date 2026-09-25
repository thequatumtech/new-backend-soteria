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
            <div class="text-white group-title fw-bold">{{__('messages.reports.travel_policies')}} {{__('messages.reports.reports')}}</div>
            @include('admin.reports.partials.report-download-controls', [
            'pdfRoute' => route('reports.download', ['report' => 'travel_policies_report', 'format' => 'pdf']),
            'excelRoute' => route('reports.download', ['report' => 'travel_policies_report', 'format' => 'excel']),
            'filePrefix' => 'Travel_Policies_Report',
            ])
        </div>
        <form id="reportForm" action="" method="GET" class="d-none">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <label for="grouptype">{{__('messages.travel_policies_report.group_report_by')}}</label>
                                {{-- <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="period_of_travel" {{ request('grouptype')=='period_of_travel' ? 'selected' : ''}}>Period of travel</option>
                                    <option value="destination" {{ request('grouptype')=='destination' ? 'selected' : ''}}>Destination</option>
                                    <option value="client_age" {{ request('grouptype')=='client_age' ? 'selected' : ''}}>Client age</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>Agent name</option>
                                    <option value="insurance_company" {{ request('grouptype')=='client_name' ? 'selected' : ''}}>Insurance company</option>
                                    <option value="travel_plan" {{ request('grouptype')=='client_name' ? 'selected' : ''}}>Travel plans</option>
                                </select> --}}
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="insurance_company" {{ request('grouptype')=='insurance_company' || empty(request('grouptype')) ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_insurance_company')}}</option>
                                    <option value="period_of_travel" {{ request('grouptype')=='period_of_travel' ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_period_of_travel')}}</option>
                                    <option value="destination" {{ request('grouptype')=='destination' ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_destination')}}</option>
                                    <option value="client_age" {{ request('grouptype')=='client_age' ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_client_age')}}</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_agent_name')}}</option>
                                    <option value="travel_plan" {{ request('grouptype')=='travel_plan' ? 'selected' : ''}}>{{__('messages.travel_policies_report.grouptype_travel_plan')}}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                               <label for="insurance_company">{{__('messages.travel_policies_report.insurance_company')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="insurance_company" id="insurance_company">
                                        <option value="">{{__('messages.travel_policies_report.select_company')}}</option>
                                    @foreach ($insuranceCompanies as $company)
                                    <option value="{{ $company->id }}" {{ request('insurance_company')==$company->id ? 'selected' : ''}}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                              <label for="issue_date">{{__('messages.travel_policies_report.from_date')}}</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="issue_date" id="issue_date" value="{{request('issue_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="expiry_date">{{__('messages.travel_policies_report.to_date')}}</label>

                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{request('expiry_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="destination">{{__('messages.travel_policies_report.destination')}}</label>

                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="destination" id="destination">
                                       <option value="">{{__('messages.travel_policies_report.select_destination')}}</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ request('destination')==$country->id ? 'selected' : ''}}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                             <label for="travel_period">{{__('messages.travel_policies_report.travel_period')}}</label>

                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="travel_period" id="travel_period">
                                  <option value="">{{__('messages.travel_policies_report.select_travel_period')}}</option>

                                    @foreach ($periods as $period)
                                    <option value="{{ $period['id'] }}" {{ request('travel_period')==$period['id'] ? 'selected' : ''}}>{{ $period['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                               <label for="plan_type">{{__('messages.travel_policies_report.plan_type')}}</label>

                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="plan_type" id="plan_type" value="{{request('plan_type')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                               <label for="agent_name">{{__('messages.travel_policies_report.agent_name')}}</label>

                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="agent_name" id="agent_name">
                                        <option value="">{{__('messages.travel_policies_report.select_agent')}}</option>

                                    @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_name')==$agent->id ? 'selected' : ''}}>{{ $agent->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_age">{{__('messages.travel_policies_report.client_age')}}</label>

                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="number" name="client_age" id="client_age" value="{{request('client_age')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <button type="submit" class="btn rounded-1 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.travel_policies_report.generate_report')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="m-2 text-center">
            <img class="p-3" src="{{asset('img/DashboardSotariaLogo.png')}}" alt="">
            <!-- <h4>Policies by
                @php
                switch(request('grouptype'))
                {
                case 'period_of_travel':
                echo "Period of travel";
                break;
                case 'destination':
                echo "Destination Country";
                break;
                case 'client_age':
                echo "Client Age";
                break;
                case 'agent_name':
                echo "Agent Name";
                break;
                case 'insurance_company':
                echo "Insurance company";
                break;
                case 'travel_plan':
                echo "Travel plans";
                break;
                default:
                echo "Insurance Company";
                break;
                }
                @endphp
            </h4>
            <p>From: {{ request('issue_date') ?? '--' }} To: {{ request('expiry_date') ?? '--' }}</p>
             -->
            <h4>{{__('messages.travel_policies_report.policies_by')}}
    @php
    switch(request('grouptype'))
    {
    case 'period_of_travel':
    echo __('messages.travel_policies_report.heading_period_of_travel');
    break;
    case 'destination':
    echo __('messages.travel_policies_report.heading_destination');
    break;
    case 'client_age':
    echo __('messages.travel_policies_report.heading_client_age');
    break;
    case 'agent_name':
    echo __('messages.travel_policies_report.heading_agent_name');
    break;
    case 'travel_plan':
    echo __('messages.travel_policies_report.heading_travel_plan');
    break;
    default:
    echo __('messages.travel_policies_report.heading_insurance_company');
    break;
    }
    @endphp
</h4>
<p>{{__('messages.travel_policies_report.from')}} {{ request('issue_date') ?? __('messages.travel_policies_report.no_date') }} {{__('messages.travel_policies_report.to')}} {{ request('expiry_date') ?? __('messages.travel_policies_report.no_date') }}</p>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            @if ($policies)
            @foreach ($policies as $company => $data)
            <h3>{{ $company }}</h3>
            <table class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th class="no-search">{{__('messages.reports.client')}}</th>
                        <th class="no-search">{{__('messages.reports.period_of_travel')}}</th>
                        <th class="no-search">{{__('messages.reports.destination')}}</th>
                        <th class="no-search">{{__('messages.sidebar_titles.age')}}</th>
                        <th class="no-search">{{__('messages.reports.agent')}}</th>
                        <th class="no-order no-search">{{__('messages.sidebar_titles.travel_plan')}}</th>
                        <th class="no-order no-search">{{__('messages.plans.net_premium')}}</th>
                        <th class="no-order no-search">{{__('messages.reports.gross_premium')}}</th>
                        <th class="no-order no-search">{{__('messages.insurance_company.insurance_company')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['policies'] as $policy)
                    <tr>
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->period_of_travel }}</td>
                        <td>{{ $policy->distination }}</td>
                        <td>{{ $policy->client_age }}</td>
                        <td>{{ $policy->agent_name }}</td>
                        <td>{{ $policy->travel_plans_name }}</td>
                        <td>{{ $policy->net_premium }}</td>
                        <td>{{ $policy->gross_premium }}</td>
                        <td>{{ $policy->company_name }}</td>
                    </tr>
                    @endforeach
                    <!-- Totals Row -->
                    <tr>
                            <td colspan="6"><strong>{{__('messages.travel_policies_report.total')}}</strong></td>

                        <td>{{ $data['totals']['total_net_premium'] }}</td>
                        <td>{{ $data['totals']['total_gross_premium'] }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            @endforeach
           @else
            <h4 class="text-center">{{__('messages.travel_policies_report.no_record')}}</h4>
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

<!-- START Delete Customers -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.delete_client')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('client.delete')}}" method="post">

                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>{{__('messages.clients.delete_confirm')}}</h4>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="hidden" id="deleteing_id" name="delete_customer_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.clients.delete')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- END Delete Customers -->

<!-- START Send Message -->
<div class="modal fade " id="SendMessageModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.messages')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('client.send_message')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>{{__('messages.clients.messages')}}</h4>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="hidden" id="send_client_id" name="send_client_id">
                                    </div>
                                    <div class="shadow-1 p-2">
                                        <textarea class="input-1" name="message" style="resize: none;min-height: 6.5rem;width: 100%;" required placeholder="{{__('messages.clients.type_here')}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.clients.send')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Send Message-->
<!-- START Add to blacklist -->
<div class="modal fade " id="AddToBlacklistModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.add_to_blacklist')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('client.add_to_blacklist')}}" method="post">

                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>{{__('messages.clients.add_to_blacklist_header')}}</h4>
                                    <span id="add_to_blacklist_clients"></span>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="hidden" id="add_to_blacklist_client_id" name="add_to_blacklist_client_id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.clients.yes')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Send Message-->
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
    <div class="p-3 col-12 col-lg-8 mx-auto">
        <div class="toast align-items-center text-bg-danger border-0 col-12 col-lg-8 w-100" data-bs-delay="3000" role="alert" aria-live="assertive" aria-atomic="true" id="clientSelectToast">
            <div class="d-flex">
                <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                </div>
            </div>
        </div>
    </div>
</div>


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