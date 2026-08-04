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
            <div class="text-white group-title fw-bold">{{__('messages.clients.client')}}</div>
            <a href="{{route('client.add')}}" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </a>
        </div>
        <div class="col-12 col-lg-6">
            <div class="pt-4 row" style="border: none;">
                <div class="col-lg-5">
                    <button type="button" id="send_message_btn" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                        style="background-color: #EF7C00;">{{__('messages.clients.send_message')}}</button>
                </div>
                <div class="col-lg-5">
                    <button type="button" id="add_to_blacklist_btn" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                        style="background-color: #EF7C00;">{{__('messages.clients.add_to_blacklist')}}</button>
            </div>
            </div>
        </div>

        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th class="no-search">{{__('messages.clients.id')}}</th>
                        <th>{{__('messages.clients.full_name')}}</th>
                        <th>{{__('messages.clients.mobile_no')}}</th>
                        <th class="no-search">{{__('messages.clients.occupation')}}</th>
                        <th class="no-search">{{__('messages.clients.agent')}}</th>
                        <th class="no-search">{{__('messages.clients.no_of_policies')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.action')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.select')}}<br/><input class="form-check-input select-all-checkbox" type="checkbox"></th>
                        <th class="no-show">Email Address</th>
                        <th class="no-show">National Id/Passport</th>
                        <th class="no-show">Residence No.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $key=> $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{$data->full_name}}</td>
                        <td>{{$data->mobile_no}}</td>
                        <td>{{$data->occupation->name}}</td>
                        <td>{{$data->agent_id && $data->agent ? $data->agent->full_name :'-'}}</td>
                        <td>{{$data->no_of_policies}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
{{--                                <a href="" class="" style="color: #939EAA !important;">More</a>--}}
                                <button type="button" class="btn p-0 m-0 btn-custom viewbtn"  value="{{$data->id}}">
                                    <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                </button>
                                <a href="{{route('client.edit',$data->id)}}" class="btn p-0 m-0" title="Edit">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </a>
                                <button class="btn p-0 m-0 deletebtn" value="{{$data->id}}" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                            </div>
                        </td>
                        <td><input class="form-check-input select-checkbox" type="checkbox" data-client_id="{{$data->id}}" data-client_name="{{$data->full_name}}"></td>
                        <td>{{$data->email_id}}</td>
                        <td>{{$data->national_id_number}}</td>
                        <td>{{$data->residence_id_number}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="selected_client_id">
    <input type="hidden" id="selected_client_names">
</main>
{{--
<!-- START ADD Customers -->
<div class="modal fade " id="addCustomer" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.agents')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('customer.create')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="full_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Email ID </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" name="email" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Occupation </div>
                                    <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" name="occupation" style="height: 3.5rem;" required>
                                        <option value="s" selected>--Select--</option>
                                        <option value="IT Engineer">IT Engineer</option>
                                        <option value="Mechanical Engineer">Mechanical Engineer</option>
                                        <option value="Civil Engineer">Civil Engineer</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="number" class="form-control" name="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Date of Birth</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="date" name="dob" id="jtime" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> House No. / Building Name </div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="housenoandbuildingname" id="jtime" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Street </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="street" class="form-control" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Country </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="country" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="india">India</option>
                                        <option value="usa">USA</option>
                                        <option value="uk">UK</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> City </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="city" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="bhavnagar">bhavnagar</option>
                                        <option value="rajkot">rajkot</option>
                                        <option value="baroda">baroda</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> District </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="district" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="dist1">dist1</option>
                                        <option value="dist2">dist2</option>
                                        <option value="dist3">dist3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12  pt-4">
                                    <div> Stamp of Insurance Comapany </div>
                                    <div class="card shadow1 border-0 rounded-0 file-upload " style="cursor: pointer;" onclick="fu()">
                                        <div class="card-body mx-auto">
                                            <div class="d-block mx-auto pt-3"> <img src="{{asset('img/icon-upload.png')}}"> </div>
                                        </div>
                                        <div class="card-body mx-auto ">
                                            <input type="file" name="stamp_of_company" class="d-none" id="1" onchange="labelc()" required>
                                            <div class="mx-auto" style="color: #ced4da;"> <label id="l1" class="custom-file-label"> Upload your File Here </label> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD </button>
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
<!-- END ADD Customers -->
<!-- START Edit & Update Customers -->
<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Edit Customer</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('customer.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id" value="">
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="full_name" name="full_name" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Email ID </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" id="email" name="email" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Occupation </div>
                                    <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" id="occupation" name="occupation" style="height: 3.5rem;">
                                        <option value="s" selected>--Select--</option>
                                        <option value="IT Engineer">IT Engineer</option>
                                        <option value="Mechanical Engineer">Mechanical Engineer</option>
                                        <option value="Civil Engineer">Civil Engineer</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="number" id="mobile_no" class="form-control" name="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Date of Birth</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="date" name="dob" id="dob" style="border: none; font-weight: 330;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> House No. / Building Name </div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="housenoandbuildingname" id="housenoandbuildingname" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Street </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="street" id="street" class="form-control" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Country </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="country" name="country" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="india">India</option>
                                        <option value="usa">USA</option>
                                        <option value="uk">UK</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> City </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="city" name="city" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="bhavnagar">bhavnagar</option>
                                        <option value="rajkot">rajkot</option>
                                        <option value="baroda">baroda</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> District </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="district" name="district" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="dist1">dist1</option>
                                        <option value="dist2">dist2</option>
                                        <option value="dist3">dist3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12  pt-4">
                                    <div> Stamp of Insurance Comapany </div>
                                    <div class="card shadow1 border-0 rounded-0 file-upload " style="cursor: pointer;" onclick="fu2()">
                                        <div class="card-body mx-auto">
                                            <div class="d-block mx-auto pt-3"> <img src="{{asset('img/icon-upload.png')}}"> </div>
                                        </div>
                                        <div class="card-body mx-auto ">
                                            <input type="file" name="stamp_of_company" class="d-none" id="2" onchange="labelc2()">
                                            <div class="mx-auto" style="color: #ced4da;"> <label id="l2" class="custom-file-label"> Upload your Excel Sheet Here </label> </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <table class="table table-striped" id="customer_edit_file">
                                            <thead>
                                                <tr>
                                                    <th>Index</th>
                                                    <th>File</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>

                                </div>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">UPDATE </button>
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
<!-- END Edit & Update Customers -->
--}}
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
                                    <input type="hidden" id="deleteing_id" name="delete_customer_id">
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
                                    <input type="hidden" id="send_client_id" name="send_client_id">
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
                                    <input type="hidden" id="add_to_blacklist_client_id" name="add_to_blacklist_client_id">
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

<!-- START View CLIENT -->
<div class="modal fade viewmodal" id="ViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.client_details')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="row view-modal-content">
                <p><strong>{{__('messages.agents.first_name')}} : </strong><span id="v_first_name"></span></p>
                <p><strong>{{__('messages.agents.father_name')}} : </strong><span id="v_father_name"></span></p>
                <p><strong>{{__('messages.agents.grandfather_name')}} : </strong><span id="v_grandfather_name"></span></p>
                <p><strong>{{__('messages.agents.surname')}} : </strong><span id="v_surname"></span></p>
                <p><strong>{{__('messages.clients.language')}} : </strong><span id="v_language"></span></p>
                <p><strong>{{__('messages.agents.nationality')}} : </strong><span id="v_nationality"></span></p>
                <p><strong>{{__('messages.agents.id_number')}} : </strong><span id="v_id_number"></span></p>
                <p><strong>{{__('messages.agents.residence_no')}} : </strong><span id="v_residence_no"></span></p>
                <p><strong>{{__('messages.agents.birth_date')}} : </strong><span id="v_birth_date"></span></p>
                <p><strong>{{__('messages.agents.gender')}} : </strong><span id="v_gender"></span></p>
                <p><strong>{{__('messages.agents.marital_status')}} : </strong><span id="v_marital_status"></span></p>
                <p><strong>{{__('messages.agents.email')}} : </strong><span id="v_email"></span></p>
                <p><strong>{{__('messages.agents.mobile_no')}} : </strong><span id="v_mobile_no"></span></p>
                <h4>{{__('messages.clients.home_address')}}</h4>
                <p><strong>{{__('messages.agents.country')}} : </strong><span id="v_country"></span></p>
                <p><strong>{{__('messages.clients.residing_country')}} : </strong><span id="v_residing_country"></span></p>
                <p><strong>{{__('messages.agents.city')}} : </strong><span id="v_city"></span></p>
                <p><strong>{{__('messages.agents.district')}} : </strong><span id="v_district"></span></p>
                <p><strong>{{__('messages.agents.street_name')}} : </strong><span id="v_street_name"></span></p>
                <p><strong>{{__('messages.agents.building_no')}} : </strong><span id="v_building_no"></span></p>
                <p><strong>{{__('messages.clients.company_name')}} : </strong><span id="v_company_name"></span></p>
                <p><strong>{{__('messages.clients.position')}} : </strong><span id="v_position"></span></p>
                <p><strong>{{__('messages.clients.work_nature')}} : </strong><span id="v_work_nature"></span></p>
                <p><strong>{{__('messages.clients.company_city')}} : </strong><span id="v_company_city"></span></p>
                <p><strong>{{__('messages.clients.company_district')}} : </strong><span id="v_company_district"></span></p>
                <p><strong>{{__('messages.clients.company_street_name')}} : </strong><span id="v_company_street_name"></span></p>
                <p><strong>{{__('messages.clients.company_building_no')}} : </strong><span id="v_company_building_no"></span></p>
                <p><strong>{{__('messages.clients.company_contact_no')}} : </strong><span id="v_company_contact_no"></span></p>
                <p><strong>{{__('messages.agents.id_front')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_front">View</a> </p>
                <p><strong>{{__('messages.agents.id_back')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_back">View</a> </p>
                <p><strong>{{__('messages.agents.profile_pic')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_profile_pic">View</a> </p>
                <p><strong>{{__('messages.agents.agent_code')}} : </strong><span id="v_agent_code"></span></p>
                <div class="has-company">
                    <p><strong>{{__('messages.clients.company_name')}} : </strong><span
                                id="v_client_company_name"></span></p>
                    <p><strong>{{__('messages.clients.company_national_id_no')}} : </strong><span
                                id="v_client_company_national_id_no"></span></p>
                    <p><strong>{{__('messages.clients.company_registration_no')}} : </strong><span
                                id="v_client_company_registration_no"></span></p>
                    <h4>{{__('messages.clients.company_address')}}</h4>
                    <p><strong>{{__('messages.agents.country')}} : </strong><span id="v_client_company_country"></span>
                    </p>
                    <p><strong>{{__('messages.agents.city')}} : </strong><span id="v_client_company_city"></span></p>
                    <p><strong>{{__('messages.agents.district')}} : </strong><span
                                id="v_client_company_district"></span></p>
                    <p><strong>{{__('messages.agents.street_name')}} : </strong><span
                                id="v_client_company_street_name"></span></p>
                    <p><strong>{{__('messages.agents.building_no')}} : </strong><span
                                id="v_client_company_building_no"></span></p>
                    <p><strong>{{__('messages.clients.office_no')}} : </strong><span
                                id="v_client_company_office_no"></span></p>
                    <p><strong>{{__('messages.clients.company_telephone_no')}} : </strong><span
                                id="v_client_company_company_telephone_no"></span></p>
                    <p><strong>{{__('messages.clients.company_owner_telephone_no')}} : </strong><span
                                id="v_client_company_company_owner_telephone_no"></span></p>
                    <p><strong>{{__('messages.clients.owner_first_name')}} : </strong><span
                                id="v_client_company_owner_first_name"></span></p>
                    <p><strong>{{__('messages.clients.owner_father_name')}} : </strong><span
                                id="v_client_company_owner_father_name"></span></p>
                    <p><strong>{{__('messages.clients.owner_grandfather_name')}} : </strong><span
                                id="v_client_company_owner_grandfather_name"></span></p>
                    <p><strong>{{__('messages.clients.owner_surname')}} : </strong><span
                                id="v_client_company_owner_surname"></span></p>
                    <p><strong>{{__('messages.clients.is_partner')}} : </strong><span id="v_is_partner"></span></p>
                    <p><strong>{{__('messages.clients.is_authorized')}} : </strong><span id="v_is_authorized"></span>
                    </p>
                    <p><strong>{{__('messages.clients.authorized_position')}} : </strong><span
                                id="v_authorized_position"></span></p>
                    <p><strong>{{__('messages.clients.is_authorization_in_registration')}} : </strong><span
                                id="v_is_authorization_in_registration"></span></p>
                    <p><strong>{{__('messages.clients.issuer_authorization_document')}} : </strong> <a
                                href="javascript:void(0);" target="_blank"
                                id="vv_issuer_authorization_document">View</a></p>
                    <p><strong>{{__('messages.clients.ownership_document')}} : </strong> <a href="javascript:void(0);"
                                                                                            target="_blank"
                                                                                            id="vv_ownership_document">View</a>
                    </p>
                    <p><strong>{{__('messages.clients.career_municipality_license')}} : </strong> <a
                                href="javascript:void(0);" target="_blank" id="vv_career_municipality_license">View</a>
                    </p>
                    <p><strong>{{__('messages.clients.company_tax_certificate')}} : </strong> <a
                                href="javascript:void(0);" target="_blank" id="vv_company_tax_certificate">View</a></p>
                    <p><strong>{{__('messages.clients.practice_certificate')}} : </strong> <a href="javascript:void(0);"
                                                                                              target="_blank"
                                                                                              id="vv_practice_certificate">View</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END View CLIENT -->


@endsection

@section('script')
<script src="{{asset('js/client.js?v=1.2')}}">
</script>
@endsection

