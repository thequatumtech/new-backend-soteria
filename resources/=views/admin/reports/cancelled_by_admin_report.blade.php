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
            <div class="text-white group-title fw-bold">{{__('messages.reports.cancelled_by_admin')}} {{__('messages.reports.reports')}}</div>
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
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>Policy Type</option>
                                    <option value="blacklisted" {{ request('grouptype')=='blacklisted' ? 'selected' : ''}}>Blacklisted</option>
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
                                <label for="policy_type">Policy Type</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="policy_type" id="policy_type">
                                    <option value="">Select Policy Type</option>
                                    @foreach ($policyTypes as $type)
                                        <option value="{{ $type['id'] }}" {{ request('policy_type')==$type['id'] ? 'selected' : ''}}>{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-4 pt-4 d-flex flex-column">
                                <label for="blacklisted">Blacklisted</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_name" id="blacklisted">
                                    <option value="">Select Blacklisted</option>
                                    @foreach ($blackList as $black)
                                        <option value="{{ $black['id'] }}" {{ request('client_name')==$black['id'] ? 'selected' : ''}}>{{ $black['first_name'] }}</option>
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
                                <th>{{__('messages.clients.company_name')}}</th>
                                <th>{{__('messages.reports.policy_type')}}</th>
                                <th class="no-search">{{__('messages.reports.policy_no')}}</th>
                                <th class="no-search">{{__('messages.reports.client')}}</th>
                                <th class="no-order no-search">{{__('messages.discount_coupons.effective_date')}}</th>
                                <th class="no-order no-search">{{__('messages.discount_coupons.expiry_date')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.gross_premium')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.black_listed')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.reason_of_cancellation')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['policies'] as $policy)
                                <tr>
                                    <td>{{ $policy->company_name }}</td>
                                    <td>{{ $policy->policy_type }}</td>
                                    <td>{{ $policy->policy_no }}</td>
                                    <td>{{ $policy->client_name }}</td>
                                    <td>{{ $policy->inception_date }}</td>
                                    <td>{{ $policy->expiry_date }}</td>
                                    <td>{{ $policy->gross_premium }}</td>
                                    <td>{{ $policy->is_blacklisted }}</td>
                                    <td>{{ $policy->gross_premium }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals Row -->
                            <tr>
                                <td colspan="6"><strong>TOTAL</strong></td>
                                <td>{{ $data['totals']['total_gross_premium'] }}</td>
                                <td></td>
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
@endsection

