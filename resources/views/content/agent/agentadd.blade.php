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

</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.page_titles.agents')}}</div>
            <!-- <div class="py-1">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </div> -->
            @if(is_admin_authorized('add_agent'))
                <button data-bs-toggle="modal" data-bs-target="#addCustomer" class="btn pe-0 add-agent-btn">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
            @endif
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>{{__('messages.table_headers.id')}}</th>
                        <th>{{__('messages.table_headers.name')}}</th>
                        <th>{{__('messages.table_headers.mobile_no')}}</th>
                        <th>{{__('messages.table_headers.agent_code')}}</th>
                        <th>{{__('messages.table_headers.joining_date')}}</th>
                        <th>{{__('messages.table_headers.no_of_policies')}}</th>
                        <th>{{__('messages.table_headers.no_of_clients')}}</th>
                        <th class="no-order">{{__('messages.table_headers.action')}}</th>
                        <th>Email Address</th>
                        <th>National Id/Passport</th>
                        <th>Residence No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agentdata as $key=>$agentsdata)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{$agentsdata->full_name}}</td>
                        <td>{{$agentsdata->agent_mobile_no}}</td>
                        <td>{{$agentsdata->agent_code}}</td>
                        <td>{{$agentsdata->joining_date}}</td>
                        <td>{{$agentsdata->no_of_policies}}</td>
                        <td>{{$agentsdata->clients_count}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                @if(is_admin_authorized('view_agent'))
                                    <button type="button" class="btn p-0 m-0 btn-custom viewbtn"
                                            value="{{$agentsdata->id}}">
                                        <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                    </button>
                                @endif
                                @if(is_admin_authorized('edit_agent'))
                                    <button class="btn p-0 m-0 editbtn" value="{{$agentsdata->id}}" title="Edit">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </button>
                                @endif
                                @if(is_admin_authorized('delete_agent'))
                                    <button class="btn p-0 m-0 btn-custom deletebtn" value="{{$agentsdata->id}}"
                                            title="Suspend">
                                        <img src="{{asset('img/icon-pause.png')}}" alt="">
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td>{{$agentsdata->agent_email}}</td>
                        <td>{{$agentsdata->nationalidpassport}}</td>
                        <td>{{$agentsdata->residence_no}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
<!-- START ADD AGENT -->
<div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.titles.add_agent')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <div class="modal-body">
                    <form action="{{route('agent.create')}}" id="add_agent" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row p-3" {{--style="color: #92959A;"--}}>
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.first_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="first_name" id="first_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.father_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="father_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.grandfather_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="grandfather_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.surname')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="surname" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.nationality')}}</div>
                                    <select name="nationality_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled selected hidden>--Select--</option>
                                        @foreach($nationalities as $single)
                                        <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.id_number')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="nationalidpassport" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.residence_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="residence_no" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.birth_date')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="date" name="birth_date" id="birth_date" max="{{\Carbon\Carbon::today()->subYears(18)->format('Y-m-d')}}" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.gender')}}</div>
                                    <select name="gender" id="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        <option value="Male">{{__('messages.agents.male')}}</option>
                                        <option value="Female">{{__('messages.agents.female')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.marital_status')}}</div>
                                    <select name="marital_status" id="marital_status" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        <option value="Single">{{__('messages.agents.single')}}</option>
                                        <option value="Married">{{__('messages.agents.married')}}</option>
                                        <option value="Divorced">{{__('messages.agents.divorced')}}</option>
                                        <option value="Widowed">{{__('messages.agents.widowed')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.email')}}</div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" id="email" name="agent_email" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.mobile_no')}}</div>
                                    <div class="shadow1 p-2">
                                        <input type="number" id="mobile_no" class="form-control number" name="agent_mobile_no" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.joining_date')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="date" name="joining_date" id="joining_date" max="{{\Carbon\Carbon::today()->format('Y-m-d')}}" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.country')}}</div>
                                    <select name="country_id" id="country" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.city')}}</div>
                                    <select name="city_id" id="city" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.district')}}</div>
                                    <select name="district_id" id="district" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.street_name')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" name="street_name" id="street_name" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.building_no')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" name="building_no" id="building_no"
                                               style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div> {{__('messages.agents.id_front')}} </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_front" id="id_front" accept="image/*,.pdf">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="id_front-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> {{__('messages.agents.id_back')}} </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_back" id="id_back" accept="image/*,.pdf">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="id_back-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> {{__('messages.agents.profile_pic')}} </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*,.pdf">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="profile_pic-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.supervisor')}}</div>
                                    <select name="supervisor_id" id="supervisor_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1 supervisor_id" style="height: 3.5rem;">
                                        @if($agentdata->isNotEmpty())
                                        <option value="" selected disabled hidden> --Select-- </option>
                                            @foreach($agentdata as $single)
                                                <option value="{{$single->id}}"
                                                        data-code="{{$single->agent_code}}">{{$single->first_name}}</option>
                                            @endforeach
                                        @else
                                            <option value="" selected disabled hidden> --No agents found-- </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.supervisor_code')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" id="supervisor_code"
                                               style="border: none; font-weight: 330;" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4 remove_supervisor_div" style="display: none">
                                    <div>{{__('messages.agents.remove_supervisor')}}</div>
                                    <div class="p-2 " style="padding-bottom: 2.5rem;">
                                        <button type="button" class="btn rounded-1 w-10 text-white opacity-50 p-2 remove_supervisor_btn">
                                            <img src="{{asset('img/icon-bg-delete.png')}}" alt="Delete" title="Delete" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 supervisor_commission">
                                <div class="col-lg-12 text-center">{{__('messages.agents.supervisor_commission')}}</div>
                                <table class="table mt-4" id="commissionTable">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.agents.line_of_business')}}</th>
                                        <th>{{__('messages.agents.commission_percentage')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($line_of_business as $single)
                                        <tr>
                                            <td>{{$single->name}}</td>
                                            <td>
                                                <input type="number" class="form-control supervisor_commission_input commissionInput"
                                                       name="supervisor_commissions[{{$single->id}}]" placeholder="{{__('messages.agents.enter_commission')}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.agent_code')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="number" name="agent_code" id="agent_code"
                                               style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 text-center">{{__('messages.agents.agent_commission')}}</div>
                                <table class="table mt-4" id="commissionTable">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.agents.line_of_business')}}</th>
                                        <th>{{__('messages.agents.commission_percentage')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($line_of_business as $single)
                                        <tr>
                                            <td>{{$single->name}}</td>
                                            <td>
                                                <input type="number" class="form-control commissionInput" required
                                                       name="agent_commissions[{{$single->id}}]" placeholder="{{__('messages.agents.enter_commission')}}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.agents.add')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>
<!-- END ADD AGENT -->

<!-- START Agent Edit Modal -->
<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.agents.edit_agent')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <div class="modal-body">
                    <form action="{{route('agent.update')}}" id="editAgent" method="post">
                        @csrf
                        <input type="hidden" name="agent_id" id="e_agent_id" value="">
                    <div class="row p-3"{{-- style="color: #92959A;"--}}>
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.first_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="first_name" id="e_first_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.father_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="father_name" id="e_father_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.grandfather_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="grandfather_name" id="e_grandfather_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-2">
                                    <div>{{__('messages.agents.surname')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="surname" id="e_surname" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.nationality')}}</div>
                                    <select name="nationality_id" id="e_nationality_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled selected hidden>--Select--</option>
                                        @foreach($nationalities as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.id_number')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="nationalidpassport" id="e_nationalidpassport" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.residence_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="residence_no" id="e_residence_no" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.birth_date')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="date" name="birth_date" id="e_birth_date" max="{{\Carbon\Carbon::today()->subYears(18)->format('Y-m-d')}}" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.gender')}}</div>
                                    <select name="gender" id="e_gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        <option value="Male">{{__('messages.agents.male')}}</option>
                                        <option value="Female">{{__('messages.agents.female')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.marital_status')}}</div>
                                    <select name="marital_status" id="e_marital_status" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        <option value="Single">{{__('messages.agents.single')}}</option>
                                        <option value="Married">{{__('messages.agents.married')}}</option>
                                        <option value="Divorced">{{__('messages.agents.divorced')}}</option>
                                        <option value="Widowed">{{__('messages.agents.widowed')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.email')}}</div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" id="e_agent_email" name="agent_email" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.mobile_no')}}</div>
                                    <div class="shadow1 p-2">
                                        <input type="number" id="e_agent_mobile_no" class="form-control number" name="agent_mobile_no" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.joining_date')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="date" name="joining_date" id="e_joining_date" max="{{\Carbon\Carbon::today()->format('Y-m-d')}}" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.country')}}</div>
                                    <select name="country_id" id="e_country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.city')}}</div>
                                    <select name="city_id" id="e_city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.district')}}</div>
                                    <select name="district_id" id="e_district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.street_name')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" name="street_name" id="e_street_name" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.building_no')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" name="building_no" id="e_building_no"
                                               style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.agents.id_front')}} </div>
                                        <a id="v_id_front" href="" target="_blank">View</a>
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_front" class="d-none" id="e_id_front" onchange="">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.agents.id_back')}} </div>
                                        <a id="v_id_back" href="" target="_blank">View</a>
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_back" class="d-none" id="e_id_back">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.agents.profile_pic')}} </div>
                                        <a id="v_profile_pic" href="" target="_blank">View</a>
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-4 mt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="profile_pic" class="d-none" id="e_profile_pic">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-3">
                                    <div>{{__('messages.agents.supervisor')}}</div>
                                    <select name="supervisor_id" id="e_supervisor_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1 e_supervisor_id" style="height: 3.5rem;">
                                        <option value="" selected disabled hidden> --Select-- </option>
                                        @foreach($agentdata as $single)
                                            <option value="{{$single->id}}" data-code="{{$single->agent_code}}">{{$single->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div>{{__('messages.agents.supervisor_code')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="text" id="e_supervisor_code"
                                               style="border: none; font-weight: 330;" disabled>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3 e_remove_supervisor_div" style="display: none">
                                    <div>{{__('messages.agents.remove_supervisor')}}</div>
                                    <div class="p-2 " style="padding-bottom: 2.5rem;">
                                        <button type="button" class="btn rounded-1 w-10 text-white opacity-50 p-2 remove_supervisor_btn">
                                            <img src="{{asset('img/icon-bg-delete.png')}}" alt="Delete" title="Delete" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 supervisor_commission">
                                <div class="col-lg-12 text-center">{{__('messages.agents.supervisor_commission')}}</div>
                                <table class="table mt-4" id="commissionTable">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.agents.line_of_business')}}</th>
                                        <th>{{__('messages.agents.commission_percentage')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($line_of_business as $single)
                                        <tr>
                                            <td>{{$single->name}}</td>
                                            <td>
                                                <input type="number" class="form-control e_supervisor_commission_input commissionInput"
                                                       name="supervisor_commissions[{{$single->id}}]"
                                                       id="e_supervisor_commissions[{{$single->id}}]"
                                                       placeholder="{{__('messages.agents.enter_commission')}}"
                                                       value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-3 pt-4">
                                    <div>{{__('messages.agents.agent_code')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="number" name="agent_code" id="e_agent_code"
                                               style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 text-center">{{__('messages.agents.agent_commission')}}</div>
                                <table class="table mt-4" id="e_commissionTable">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.agents.line_of_business')}}</th>
                                        <th>{{__('messages.agents.commission_percentage')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($line_of_business as $single)
                                        <tr>
                                            <td>{{$single->name}}</td>
                                            <td>
                                                <input type="number" class="form-control commissionInput" required
                                                       name="agent_commissions[{{$single->id}}]"
                                                       id="e_agent_commissions[{{$single->id}}]"
                                                       placeholder="{{__('messages.agents.enter_commission')}}"
                                                       value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.agents.update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
        </div>
    </div>
</div>
<!-- END Agent Edit Modal -->

<!-- START delete AGENT -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Suspend Agent</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('destoryAgent')}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3"{{-- style="color: #92959A;"--}}>
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-8">
                                    <h4>Confirm to Suspend Agent ?</h4>
                                    <input type="hidden" id="deleteing_id" name="delete_agent_id">
                                </div>
                                <div class="col-12 col-lg-2">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit"
                                                class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                                style="background-color: #EF7C00;">YES
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="button"
                                                class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                                style="background-color: #EF7C00;">NO
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END ADD AGENT -->

<!-- START View AGENT -->
<div class="modal fade viewmodal" id="ViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.agents.agent')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="row view-modal-content">
                <p><strong>{{__('messages.agents.first_name')}} : </strong><span id="v_first_name"></span></p>
                <p><strong>{{__('messages.agents.father_name')}} : </strong><span id="v_father_name"></span></p>
                <p><strong>{{__('messages.agents.grandfather_name')}} : </strong><span id="v_grandfather_name"></span></p>
                <p><strong>{{__('messages.agents.surname')}} : </strong><span id="v_surname"></span></p>
                <p><strong>{{__('messages.agents.nationality')}} : </strong><span id="v_nationality_id"></span></p>
                <p><strong>{{__('messages.agents.id_number')}} : </strong><span id="v_nationalidpassport"></span></p>
                <p><strong>{{__('messages.agents.residence_no')}} : </strong><span id="v_residence_no"></span></p>
                <p><strong>{{__('messages.agents.birth_date')}} : </strong><span id="v_birth_date"></span></p>
                <p><strong>{{__('messages.agents.gender')}} : </strong><span id="v_gender"></span></p>
                <p><strong>{{__('messages.agents.marital_status')}} : </strong><span id="v_marital_status"></span></p>
                <p><strong>{{__('messages.agents.email')}} : </strong><span id="v_agent_email"></span></p>
                <p><strong>{{__('messages.agents.mobile_no')}} : </strong><span id="v_agent_mobile_no"></span></p>
                <p><strong>{{__('messages.agents.joining_date')}} : </strong><span id="v_joining_date"></span></p>
                <p><strong>{{__('messages.agents.country')}} : </strong><span id="v_country_id"></span></p>
                <p><strong>{{__('messages.agents.city')}} : </strong><span id="v_city_id"></span></p>
                <p><strong>{{__('messages.agents.district')}} : </strong><span id="v_district_id"></span></p>
                <p><strong>{{__('messages.agents.street_name')}} : </strong><span id="v_street_name"></span></p>
                <p><strong>{{__('messages.agents.building_no')}} : </strong><span id="v_building_no"></span></p>
                <p><strong>{{__('messages.agents.id_front')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_front">View</a> </p>
                <p><strong>{{__('messages.agents.id_back')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_back">View</a> </p>
                <p><strong>{{__('messages.agents.profile_pic')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_profile_pic">View</a> </p>
                <p><strong>{{__('messages.agents.supervisor')}} : </strong><span id="v_supervisor_name"></span></p>
                <p><strong>{{__('messages.agents.supervisor_code')}} : </strong><span id="v_supervisor_id"></span></p>
                <p><strong>{{__('messages.agents.agent_code')}} : </strong><span id="v_agent_code"></span></p>
                <div class="v-has-commission">
                <div class="col-lg-12 text-center">{{__('messages.agents.supervisor_commission')}}</div>
                <table class="table mt-4 table-sm table-bordered" id="commissionTable">
                    <thead>
                    <tr>
                        <th>{{__('messages.agents.line_of_business')}}</th>
                        <th>{{__('messages.agents.commission_percentage')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($line_of_business as $single)
                        <tr>
                            <td>{{$single->name}}</td>
                            <td>
                                <span id="v_supervisor_commissions[{{$single->id}}]">0</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                <div class="col-lg-12 text-center">{{__('messages.agents.agent_commission')}}</div>
                <table class="table mt-4 table-sm table-bordered" id="e_commissionTable">
                    <thead>
                    <tr>
                        <th>{{__('messages.agents.line_of_business')}}</th>
                        <th>{{__('messages.agents.commission_percentage')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($line_of_business as $single)
                        <tr>
                            <td>{{$single->name}}</td>
                            <td>
                                <span id="v_agent_commissions[{{$single->id}}]">0</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- END View AGENT -->
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
        let checkMobileUrl = "{{ route('agents.check_mobile') }}";
        let checkEmailUrl = "{{ route('agents.check_email') }}";
    </script>
    <script src="{{asset('js/agent.js?v=1.4')}}"></script>
@endsection
