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
        color: inherit; /* Set the color to inherit */
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.reports.client_reports')}}</div>
            <a href="javascript:void(0)" id="toggleForm" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </a>
        </div>
        <!-- Form to be toggled -->
        <form id="reportForm" action="" method="GET" class="d-none">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <label for="grouptype">Group Report By</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="grouptype" id="grouptype" onchange="toggleDateFields()">
                                    <option value="insurance_company" {{ request('grouptype')=='insurance_company' ? 'selected' : ''}}>Insurance Company</option>
                                    <option value="position" {{ request('grouptype')=='position' ? 'selected' : ''}}>Position</option>
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>Policy Type</option>
                                    <option value="gender" {{ request('grouptype')=='gender' ? 'selected' : ''}}>Gender</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>Agent</option>
                                    <option value="policy_plan" {{ request('grouptype')=='policy_plan' ? 'selected' : ''}}>Policy Plan</option>
                                    <option value="city" {{ request('grouptype')=='city' ? 'selected' : ''}}>City</option>
                                    <option value="district" {{ request('grouptype')=='district' ? 'selected' : ''}}>District</option>
                                    <option value="marital_status" {{ request('grouptype')=='marital_status' ? 'selected' : ''}}>Marital Status</option>
                                    <option value="age" {{ request('grouptype')=='age' ? 'selected' : ''}}>Age</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="insurance_company">Insurance Company</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="insurance_company" id="insurance_company">
                                    <option value="">Select Company</option>
                                    @foreach ($insuranceCompanies as $company)
                                        <option value="{{ $company->id }}" {{ request('insurance_company')==$company->id ? 'selected' : ''}}>{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="issue_date">Issue Date</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;"> 
                                    <input type="date" name="issue_date" id="issue_date" value="{{request('issue_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="expiry_date">Expiry Date</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;"> 
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{request('expiry_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_age">Client Age</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_age" id="client_age">
                                    <option value="">Select Client Age</option>
                                    @foreach ($ages as $age)
                                        <option value="{{ $age->age }}" {{ request('client_age')==$age->age ? 'selected' : ''}}>{{ $age->age }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_position">Client Position</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_position" id="client_position">
                                    <option value="">Select Client Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}" {{ request('client_position')==$position->id ? 'selected' : ''}}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_district">Client district</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_district" id="client_district">
                                    <option value="">Select Client district</option>
                                    @foreach ($district as $district)
                                        <option value="{{ $district->id }}" {{ request('client_district')==$district->id ? 'selected' : ''}}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="policy_type">Policy Type</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="policy_type" id="policy_type">
                                    <option value="">Select Policy Type</option>
                                    @foreach ($policyTypes as $type)
                                        <option value="{{ $type['id'] }}" {{ request('policy_type')==$type['id'] ? 'selected' : ''}}>{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_gender">Client Gender</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_gender" id="client_gender">
                                    <option value="">Select Client Gender</option>
                                    <option value="1" {{ request('client_gender')==1 ? 'selected' : ''}}>Male</option>
                                    <option value="2" {{ request('client_gender')==2 ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_city">Client City</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_city" id="client_city">
                                    <option value="">Select Client City</option>
                                    @foreach ($city as $city)
                                        <option value="{{ $city->id }}" {{ request('client_city')==$city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="policy_plan">Policy Plan</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="policy_plan" id="policy_plan">
                                    <option value="">Select Policy Plan</option>
                                    @foreach ($policy_plan as $plan)
                                        <option value="{{ $plan->plan_name }}" {{ request('policy_plan')==$plan->plan_name ? 'selected' : ''}}>{{ $plan->plan_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_marital">Client Marital</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_marital" id="client_marital">
                                    <option value="">Select Client Marital</option>
                                    <option value="1" {{ request('client_marital')==1 ? 'selected' : ''}}>Single</option>
                                    <option value="2" {{ request('client_marital')==2 ? 'selected' : ''}}>Married</option>
                                    <option value="3" {{ request('client_marital')==3 ? 'selected' : ''}}>Divorced</option>
                                    <option value="4" {{ request('client_marital')==4 ? 'selected' : ''}}>Widowed</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="client_name">Clients’ Name</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_name" id="client_name">
                                    <option value="">Select Clients’ Name</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_name')==$client->id ? 'selected' : ''}}>{{ $client->first_name }} {{ $client->father_name }} {{ $client->surname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <button type="submit" class="btn rounded-1 text-white opacity-50 p-2" style="background-color: #EF7C00;">Generate Report</button>
                            </div>
                               <!-- <div class="col-12 col-lg-4 pt-4 d-flex gap-2 align-items-end">
                                <button type="submit" class="btn rounded-1 text-white opacity-50 p-2 flex-grow-1" style="background-color: #EF7C00;">Generate</button>
                                <button type="submit" name="export" value="excel" class="btn rounded-1 text-white opacity-50 p-2 flex-grow-1" style="background-color: #28a745;">Excel</button>
                                <button type="submit" name="export" value="pdf" class="btn rounded-1 text-white opacity-50 p-2 flex-grow-1" style="background-color: #dc3545;">PDF</button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="m-2 text-center">
            <img class="p-3" src="{{asset('img/DashboardSotariaLogo.png')}}" alt="">
            <h4>
                @php
                    switch(request('grouptype'))
                    {
                        case 'insurance_company':
                            echo "Client's By Insurance Company";
                            break;
                        case 'position':
                            echo "Client's By Position";
                            break;
                        case 'policy_type':
                            echo "Client's By Policy Type";
                            break;
                        case 'gender':
                            echo "Client's By Gender";
                            break;
                        case 'agent_name':
                            echo "Client's By Agent Name";
                            break;
                        case 'policy_plan':
                            echo "Client's By Policy Plan";
                            break;
                        case 'city':
                            echo "Client's By City";
                            break;
                        case 'district':
                            echo "Client's By District";
                            break;
                        case 'marital_status':
                            echo "Client's By Marital Status";
                            break;
                        case 'age':
                            echo "Client's By Age";
                            break;
                        default:
                            echo "Client's By Insurance Company";
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
                                <th class="no-search">{{__('messages.sidebar_titles.age')}}</th>
                                <th class="no-search">{{__('messages.clients.position')}}</th>
                                <th class="no-search">{{__('messages.sidebar_titles.district')}}</th>
                                <th class="no-search">{{__('messages.insurance_company.city')}}</th>
                                <th>{{__('messages.reports.policy_type')}}</th>
                                <th>{{__('messages.reports.policy_plan')}}</th>
                                <th>{{__('messages.reports.clients_gender')}}</th>
                                <th>{{__('messages.clients.marital_status')}}</th>
                                <th class="no-order no-search">{{__('messages.claims.effective_date')}}</th>
                                <th class="no-order no-search">{{__('messages.claims.expiry_date')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.gross_premium')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['policies'] as $policy)
                                <tr>
                                    <td>{{ $policy->client_name }}</td>
                                    <td>{{ $policy->age }}</td>
                                    <td>{{ $policy->position }}</td>
                                    <td>{{ $policy->district }}</td>
                                    <td>{{ $policy->city }}</td>
                                    <td>{{ $policy->policy_type }}</td>
                                    <td>{{ $policy->plan_name }}</td>
                                    <td>{{ $policy->gender}}</td>
                                    <td>{{ $policy->marital_status }}</td>
                                    <td>{{ $policy->start_date }}</td>
                                    <td>{{ $policy->expiry_date }}</td>
                                    <td>{{ $policy->gross_premium }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals Row -->
                            <tr>
                                <td colspan="11" style="text-align: end !important;"><strong>TOTAL</strong></td>
                                <td>{{ $data['totals']['total_gross_premium'] }}</td>
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
@endsection

