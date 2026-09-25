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

@php
extract(getAdminViewData());
@endphp

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold">{{__('messages.sub_admins.sub_admins')}}</div>
    @if(canAccessRoute('sub_admin.add'))
        <a href="{{route('sub_admin.add')}}" class="btn pe-0">
            <img src="{{asset('img/icon-add.png')}}" alt="">
        </a>
    @endif
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                        <tr>
                            <th>{{__('messages.sub_admins.full_name')}}</th>
                            <th>{{__('messages.sub_admins.mobile_no')}}</th>
                            <th>{{__('messages.sub_admins.id_code')}}</th>
                            <th>{{__('messages.sub_admins.email')}}</th>
                            <th class="no-order no-search">{{__('messages.sub_admins.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $single)
                            <tr>
                                <td>{{ $single->full_name }}</td>
                                <td>{{ $single->mobile_no }}</td>
                                <td>{{ $single->admin_id }}</td>
                                <td>{{ $single->email }}</td>
                                <td>
                                    <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                        @if(canAccessRoute('sub_admin.edit'))
                                            <a href="{{ route('sub_admin.edit', encrypt($single->id)) }}"  class="btn p-0 m-0" title="Edit">
                                                <img src="{{asset('img/icon-edit.png')}}" alt="">
                                            </a>
                                         @endif
                                        @if(canAccessRoute('sub_admin.delete'))
                                            <button class="btn p-0 m-0 deletebtn"  value="{{ encrypt($single->id) }}" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                         @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- START Delete -->

    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5">{{__('messages.sub_admins.delete')}}</h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('sub_admin.delete')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4>{{__('messages.clients.delete_confirm')}}</h4>
                                        <input type="hidden" id="deleting_id" name="delete_sub_admin_id">
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

    <!-- END Delete -->

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
<script src="{{asset('js/sub_admin.js')}}">
</script>
@endsection

