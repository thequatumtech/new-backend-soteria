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
        -moz-appearance: textfield; /* Firefox */
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
    .file-margin{
        text-align:center;
    }
    .file-margin .error{
        text-align:center;
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.sidebar_titles.claims_report')}}</div>
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
                                    <option value="client_name" {{ request('grouptype')=='client_name' ? 'selected' : ''}}>Client Name</option>
                                    <option value="policy_type" {{ request('grouptype')=='policy_type' ? 'selected' : ''}}>Policy Type</option>
                                    <option value="claim_status" {{ request('grouptype')=='claim_status' ? 'selected' : ''}}>Claim Status</option>
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
                                <label for="issue_date">From Date(ISSUE DATE)</label>
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
                                <label for="client_name">Clients’ Name</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="client_name" id="client_name">
                                    <option value="">Select Clients’ Name</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_name')==$client->id ? 'selected' : ''}}>{{ $client->first_name }} {{ $client->father_name }} {{ $client->surname }}</option>
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
                                <label for="claim_status">Claim Status</label>
                                <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" name="claim_status" id="claim_status">
                                    <option value="">Select Claim Status</option>
                                    <option value="under review">Under Review</option>
                                    <option value="received">Received</option>
                                    <option value="closed">Closed</option>
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
            <h4>Claims by 
                @php
                    switch(request('grouptype'))
                    {
                        case 'insurance_company':
                            echo "Insurance Company";
                            break;
                        case 'policy_type':
                            echo "Policy Type";
                            break;
                        case 'client_name':
                            echo "Client Name";
                            break;
                        case 'claim_status':
                            echo "Claims Status";
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
                                <th class="no-search">{{__('messages.reports.policy_no')}}</th>
                                <th class="no-search">{{__('messages.claims.insurance_company')}}</th>
                                <th class="no-search">{{__('messages.reports.client')}}</th>
                                <th>{{__('messages.claims.policy_type')}}</th>
                                <th class="no-search">{{__('messages.claims.claim_status')}}</th>
                                <th class="no-order no-search">{{__('messages.claims.claim_no')}}</th>
                                <th class="no-order no-search">{{__('messages.reports.claim_amount')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['policies'] as $policy)
                                <tr>
                                    <td>{{ $policy->policy_no }}</td>
                                    <td>{{ $policy->company_name }}</td>
                                    <td>{{ $policy->client_name }}</td>
                                    <td>{{ $policy->policy_type }}</td>
                                    <td>{{ $policy->status }}</td>
                                    <td>{{ $policy->claim_no }}</td>
                                    <td>{{ $policy->gross_premium }}</td>
                                </tr>
                            @endforeach
                            <!-- Totals Row -->
                            <tr>
                                <td colspan="6"><strong>TOTAL</strong></td>
                                <td>{{ $data['totals']['total_claim_amount'] }}</td>
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
<script>
$(document).ready(function(){
  // Show or hide date fields on page load based on the selected value
  toggleDateFields();

  // Show or hide date fields when the group type is changed
  $("#grouptype").on('change', function() {
    toggleDateFields();
  });

  // Function to toggle date fields
  function toggleDateFields() {
    if($("#grouptype").val() === 'date') {
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
@endsection

