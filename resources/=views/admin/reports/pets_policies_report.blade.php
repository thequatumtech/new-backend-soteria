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
            <div class="text-white group-title fw-bold">{{__('messages.reports.pets_policies')}} {{__('messages.reports.reports')}}</div>
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
                                    <option value="pets_type" {{ request('grouptype')=='pets_type' ? 'selected' : ''}}>Pet Type</option>
                                    <option value="pet_breed" {{ request('grouptype')=='pet_breed' ? 'selected' : ''}}>Pet Breed</option>
                                    <option value="agent_name" {{ request('grouptype')=='agent_name' ? 'selected' : ''}}>Agent Name</option>
                                    <option value="owner_city" {{ request('grouptype')=='owner_city' ? 'selected' : ''}}>Owner City</option>
                                    <option value="owner_district" {{ request('grouptype')=='owner_district' ? 'selected' : ''}}>Owner District</option>
                                    <option value="owner_age" {{ request('grouptype')=='owner_age' ? 'selected' : ''}}>Owner Age</option>
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
                                <label for="issue_date">From Date</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;"> 
                                    <input type="date" name="issue_date" id="issue_date" value="{{request('issue_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="expiry_date">To Date</label>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;"> 
                                    <input type="date" name="expiry_date" id="expiry_date" value="{{request('expiry_date')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="pets_type">Pet Type</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="pets_type" id="pets_type">
                                    <option value="">Select Pet Type</option>
                                    <option value="1" {{ request('pets_type')==1 ? 'selected' : ''}}>Dog</option>
                                    <option value="2" {{ request('pets_type')==2 ? 'selected' : ''}}>Cat</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="pet_breed">Pet Breed</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="pet_breed" id="pet_breed">
                                    <option value="">Select Pet Breed</option>
                                    @foreach ($breed as $breeds)
                                        <option value="{{ $breeds->breed }}" {{ request('pet_breed')==$breeds->breed ? 'selected' : ''}}>{{ $breeds->breed }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="agent_name">Agent Name</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="agent_name" id="agent_name">
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ request('agent_name')==$agent->id ? 'selected' : ''}}>{{ $agent->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="owner_city">Owner City</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="owner_city" id="owner_city">
                                    <option value="">Select Owner City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ request('owner_city')==$city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="owner_district">Owner district</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="owner_district" id="owner_district">
                                    <option value="">Select Owner district</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('owner_district')==$district->id ? 'selected' : ''}}>{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="owner_age">Owner Age</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="owner_age" id="owner_age">
                                    <option value="">Owner Age</option>
                                    @foreach ($ages as $age)
                                        <option value="{{ $age->id }}" {{ request('owner_age')==$age->id ? 'selected' : ''}}>{{ $age->age }}</option>
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
            <h4>Policies by
                @php
                    switch(request('grouptype'))
                    {
                        case 'pets_type':
                            echo "Pet Type";
                            break; 
                        case 'pet_breed':
                            echo "Pet Breed";
                            break;
                        case 'agent_name':
                            echo "Agent Name";
                            break;
                        case 'owner_city':
                            echo "Owner City";
                            break;
                        case 'owner_district':
                            echo "Owner Districts";
                            break;
                        case 'owner_age':
                            echo "Owner Age";
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
                                <th class="no-search">{{__('messages.claims.client_name')}}</th>
                                <th class="no-search">{{__('messages.age.age')}}</th>
                                <th class="no-order no-search">{{__('messages.discount_coupons.effective_date')}}</th>
                                <th class="no-order no-search">{{__('messages.discount_coupons.expiry_date')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.pet_type')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.pet_breed')}}</th>
                                <th class="no-order no-search">{{__('messages.insurance_company.city')}}</th>
                                <th class="no-order no-search">{{__('messages.insurance_company.district')}}</th>
                                <th class="no-order no-search">{{__('messages.sidebar_titles.agents')}}</th>
                                <th class="no-order no-search">{{__('messages.plans.net_premium')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.gross_premium')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['policies'] as $policy)
                                <tr>
                                    <td>{{ $policy->client_name }}</td>
                                    <td>{{ $policy->owner_age }}</td>
                                    <td>{{ $policy->inception_date }}</td>
                                    <td>{{ $policy->expiry_date }}</td>
                                    <td>{{ $policy->pets_type==1 ? 'Dog' : 'Cat' }}</td>
                                    <td>{{ $policy->pet_breed }}</td>
                                    <td>{{ $policy->owner_city }}</td>
                                    <td>{{ $policy->owner_district }}</td>
                                    <td>{{ $policy->agent_name }}</td>
                                    <td>{{ $policy->net_premium }}</td>
                                    <td>{{ $policy->gross_premium }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals Row -->
                            <tr>
                                <td colspan="9"><strong>TOTAL</strong></td>
                                <td>{{ $data['totals']['total_net_premium'] }}</td>
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

