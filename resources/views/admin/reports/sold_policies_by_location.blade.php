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

    /* Prevent number input spinner */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        /* Firefox */
    }

    .radio-class {
        float: left;
        clear: none;
    }

    .radio-label {
        float: left;
        clear: none;
        display: block;
        padding: 0px 1em 0px 8px;
    }

    input[type=radio],
    input.radio {
        float: left;
        clear: none;
        margin: 2px 0 0 2px;
    }

    input[type="file"] {
        height: 50px;
        cursor: pointer;
        margin-top: -40px;
        opacity: 0;
        position: relative;
    }

    .file-margin {
        text-align: center;
    }

    .file-margin .error {
        text-align: center;
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.sidebar_titles.sold_policies_by_location')}} {{__('messages.reports.reports')}}</div>
            @include('admin.reports.partials.report-download-controls', [
            'pdfRoute' => route('reports.download', ['report' => 'sold_policies_by_location', 'format' => 'pdf']),
            'excelRoute' => route('reports.download', ['report' => 'sold_policies_by_location', 'format' => 'excel']),
            'filePrefix' => 'Sold_Policies_By_Location_Report',
            ])
        </div>
        <!-- Form to be toggled -->
        <form id="reportForm" action="" method="GET" class="d-none">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                 <label for="grouptype">{{__('messages.sold_policies_by_location.group_report_by')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="insurance_company" {{ request('grouptype')=='insurance_company' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_insurance_company')}}</option>
                                    <option value="client_name" {{ request('grouptype')=='client_name' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_client_name')}}</option>
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_policy_type')}}</option>
                                    <option value="city" {{ request('grouptype')=='city' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_city')}}</option>
                                    <option value="district" {{ request('grouptype')=='district' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_district')}}</option>
                                    <option value="position" {{ request('grouptype')=='position' ? 'selected' : ''}}>{{__('messages.sold_policies_by_location.grouptype_position')}}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="insurance_company">{{__('messages.sold_policies_by_location.insurance_company')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="insurance_company" id="insurance_company">
                                    <option value="">{{__('messages.sold_policies_by_location.select_company')}}</option>
                                    @foreach ($insuranceCompanies as $company)
                                    <option value="{{ $company->id }}" {{ request('insurance_company')==$company->id ? 'selected' : ''}}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="issue_date">{{__('messages.sold_policies_by_location.from_date')}}</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="issue_date" id="issue_date" value="{{request('issue_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="expiry_date">{{__('messages.sold_policies_by_location.to_date')}}</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{request('expiry_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="agent_name">{{__('messages.sold_policies_by_location.agent_name')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="agent_name" id="agent_name">
                                    <option value="">{{__('messages.sold_policies_by_location.select_agent')}}</option>
                                    @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_name')==$agent->id ? 'selected' : ''}}>{{ $agent->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_name">{{__('messages.sold_policies_by_location.client_name')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_name" id="client_name">
                                    <option value="">{{__('messages.sold_policies_by_location.select_client_name')}}</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_name')==$client->id ? 'selected' : ''}}>{{ $client->first_name }} {{ $client->father_name }} {{ $client->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="policy_type">{{__('messages.sold_policies_by_location.policy_type')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="policy_type" id="policy_type">
                                    <option value="">{{__('messages.sold_policies_by_location.select_policy_type')}}</option>
                                    @foreach ($policyTypes as $type)
                                    <option value="{{ $type['id'] }}" {{ request('policy_type')==$type['id'] ? 'selected' : ''}}>{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_city">{{__('messages.sold_policies_by_location.client_city')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_city" id="client_city">
                                    <option value="">{{__('messages.sold_policies_by_location.select_client_city')}}</option>
                                    @foreach ($city as $city)
                                    <option value="{{ $city->id }}" {{ request('client_city')==$city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_district">{{__('messages.sold_policies_by_location.client_district')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_district" id="client_district">
                                    <option value="">{{__('messages.sold_policies_by_location.select_client_district')}}</option>
                                    @foreach ($district as $district)
                                    <option value="{{ $district->id }}" {{ request('client_district')==$district->id ? 'selected' : ''}}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_position">{{__('messages.sold_policies_by_location.client_position')}}</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_position" id="client_position">
                                    <option value="">{{__('messages.sold_policies_by_location.select_client_position')}}</option>
                                    @foreach ($positions as $position)
                                    <option value="{{ $position->id }}" {{ request('client_position')==$position->id ? 'selected' : ''}}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <button type="submit" class="btn rounded-1 text-white opacity-50 p-2" style="background-color: #EF7C00;">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="m-2 text-center">
            <img class="p-3" src="{{asset('img/DashboardSotariaLogo.png')}}" alt="">
            <!-- <h4>Sales by
                @php
                switch(request('grouptype'))
                {
                case 'insurance_company':
                echo "Insurance Company";
                break;
                case 'position':
                echo "Position";
                break;
                case 'policy_type':
                echo "Policy Type";
                break;
                case 'agent_name':
                echo "Agent Name";
                break;
                case 'client_name':
                echo "Client Name";
                break;
                case 'city':
                echo "City";
                break;
                case 'district':
                echo "District";
                break;
                default:
                echo "Insurance Company";
                break;
                }
                @endphp
            </h4>
            <p>From: {{ request('issue_date') ?? '--' }} To: {{ request('expiry_date') ?? '--' }}</p> -->
  <h4>{{__('messages.sold_policies_by_location.sales_by')}}
                @php
                switch(request('grouptype'))
                {
                case 'insurance_company':
                echo __('messages.sold_policies_by_location.heading_insurance_company');
                break;
                case 'position':
                echo __('messages.sold_policies_by_location.heading_position');
                break;
                case 'policy_type':
                echo __('messages.sold_policies_by_location.heading_policy_type');
                break;
                case 'agent_name':
                echo __('messages.sold_policies_by_location.heading_agent_name');
                break;
                case 'client_name':
                echo __('messages.sold_policies_by_location.heading_client_name');
                break;
                case 'city':
                echo __('messages.sold_policies_by_location.heading_city');
                break;
                case 'district':
                echo __('messages.sold_policies_by_location.heading_district');
                break;
                default:
                echo __('messages.sold_policies_by_location.heading_insurance_company');
                break;
                }
                @endphp
            </h4>
            <p>{{__('messages.sold_policies_by_location.from')}} {{ request('issue_date') ?? __('messages.sold_policies_by_location.no_date') }} {{__('messages.sold_policies_by_location.to')}} {{ request('expiry_date') ?? __('messages.sold_policies_by_location.no_date') }}</p>
  
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            @if ($policies)
            @foreach ($policies as $company => $data)
            <h3>{{ $company }}</h3>
            <table class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th class="no-search">{{__('messages.reports.policy_no')}}</th>
                        <th class="no-search">{{__('messages.reports.client')}}</th>
                        <th class="no-search">{{__('messages.claims.insurance_company')}}</th>
                        <th>{{__('messages.claims.policy_type')}}</th>
                        <th class="no-order no-search">{{__('messages.discount_coupons.effective_date')}}</th>
                        <th class="no-order no-search">{{__('messages.discount_coupons.expiry_date')}}</th>
                        <th class="no-search">{{__('messages.reports.agent')}}</th>
                        <th class="no-search">{{__('messages.insurance_company.city')}}</th>
                        <th class="no-search">{{__('messages.sidebar_titles.district')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.position')}}</th>
                        <th class="no-order no-search">{{__('messages.plans.gross_premium')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['policies'] as $policy)
                    <tr>
                        <td>{{ $policy->policy_no }}</td>
                        <td>{{ $policy->client_name }}</td>
                        <td>{{ $policy->company_name }}</td>
                        <td>{{ $policy->policy_type }}</td>
                        <td>{{ $policy->inception_date }}</td>
                        <td>{{ $policy->expiry_date }}</td>
                        <td>{{ $policy->agent_name }}</td>
                        <td>{{ $policy->city }}</td>
                        <td>{{ $policy->district }}</td>
                        <td>{{ $policy->position }}</td>
                        <td>{{ $policy->gross_premium }}</td>
                    </tr>
                    @endforeach
                    <!-- Totals Row -->
                    <tr>
                        <td colspan="10"><strong>{{__('messages.sold_policies_by_location.total')}}</strong></td>
                        <td>{{ $data['totals']['total_gross_premium'] }}</td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            @else
            <h4 class="text-center">{{__('messages.sold_policies_by_location.no_record')}}</h4>
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
<script>
    $(document).ready(function() {
        // Show or hide date fields on page load based on the selected value
        toggleDateFields();

        // Show or hide date fields when the group type is changed
        $("#grouptype").on('change', function() {
            toggleDateFields();
        });

        // Function to toggle date fields
        function toggleDateFields() {
            if ($("#grouptype").val() === 'date') {
                $("#dateFields").show();
                $("#endDateField").show();
            } else {
                $("#dateFields").hide();
                $("#endDateField").hide();
            }
        }
    });
</script>
<script src="{{asset('js/client.js?v=1.2')}}">
</script>
@include('admin.reports.partials.report-download-js')
@endsection