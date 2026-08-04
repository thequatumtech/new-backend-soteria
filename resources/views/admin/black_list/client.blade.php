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
            <div class="text-white group-title fw-bold">{{__('messages.black_lists.black_list')}}</div>
{{--
            <a href="{{route('client.add')}}" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </a>
--}}
        </div>
        <div class="col-12 col-lg-6">
            <div class="pt-4 row" style="border: none;">
{{--
                <div class="col-lg-5">
                    <button type="button" id="send_message_btn" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                        style="background-color: #EF7C00;">{{__('messages.clients.send_message')}}</button>
                </div>
--}}
                <div class="col-lg-5">
                    <button type="button" id="add_to_blacklist_btn"
                            class="btn rounded-1 w-100 text-white opacity-50 p-2"
                            style="background-color: #EF7C00;">{{__('messages.black_lists.reinstate')}}</button>
                </div>
                <div class="col-lg-5">
                    <button type="button" id="send_list_email_btn"
                            class="btn rounded-1 w-100 text-white opacity-50 p-2"
                            style="background-color: #EF7C00;">{{__('messages.black_lists.send_list')}}</button>
                </div>
            </div>
        </div>

        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th class="no-search">{{__('messages.black_lists.no')}}</th>
                        <th>{{__('messages.black_lists.national_id')}}</th>
                        <th>{{__('messages.black_lists.name')}}</th>
                        <th>{{__('messages.black_lists.mobile')}}</th>
                        <th>{{__('messages.black_lists.reason')}}</th>
                        <th class="no-order no-search">{{__('messages.black_lists.action')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.select')}}<br/><input class="form-check-input select-all-checkbox" type="checkbox"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $key=> $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{$data->national_id_number}}</td>
                        <td>{{$data->full_name}}</td>
                        <td>{{$data->mobile_no}}</td>
                        <td>{!! $data->black_list_reason?:'-' !!}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">

                                <a href="{{route('black_list_client',$data->id)}}" class="" style="color: #939EAA !important;">
                                    <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                </a>
                                <a href="{{route('edit_black_list_client',$data->id)}}" class="btn p-0 m-0" title="Edit">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </a>
{{--                                <button class="btn p-0 m-0 deletebtn" value="{{$data->id}}" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>--}}
                            </div>
                        </td>
                        <td><input class="form-check-input select-checkbox" type="checkbox" data-client_id="{{$data->id}}" data-client_name="{{$data->full_name}}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="selected_client_id">
    <input type="hidden" id="selected_client_names">
</main>

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

<!-- START Send List to email -->
<div class="modal fade " id="SendMessageModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.black_lists.send_list_to')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('send_blacklist_email')}}" id="send_blacklist_email_form" method="post">

                @csrf
                <input type="hidden" name="search_query" id="search_query">
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row ">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <h4>{{__('messages.black_lists.admin_email')}}</h4>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="email" name="admin_email" id="admin_email" style="border: none;" required value="" aria-required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-3 pt-3">
                                    <div class="pt-4" style="border: none;">
                                        <button data-bs-target="#notif" type="submit"
                                                class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                                style="background-color: #EF7C00;">{{__('messages.clients.send')}}</button>
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
<!-- END Send List to email-->


<!-- START Add to blacklist -->
<div class="modal fade " id="AddToBlacklistModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.black_lists.reinstate')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('restore_black_list')}}" method="post">

                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>{{__('messages.black_lists.reinstate_header')}}</h4>
                                    <span id="add_to_blacklist_clients"></span>
                                    <input type="hidden" id="add_to_blacklist_client_id" name="remove_from_blacklist_client_id">
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
<script src="{{asset('js/black_list.js')}}">
</script>
@endsection

