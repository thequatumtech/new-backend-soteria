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
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.discount_coupons.coupons_list')}}</div>
            <!-- <div class="py-1">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </div> -->
            @if(is_admin_authorized('coupons.add'))
            <a href="{{route('coupons.add')}}" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </a>
            @endif
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>{{__('messages.discount_coupons.coupon_no')}}</th>
                        <th>{{__('messages.discount_coupons.date')}}</th>
                        <th>{{__('messages.discount_coupons.coupon_code')}}</th>
                        <th>{{__('messages.discount_coupons.insurance_type')}}</th>
                        <th>{{__('messages.discount_coupons.insurance_company')}}</th>
                        <th>{{__('messages.discount_coupons.percentage')}}</th>
                        <th class="no-order no-search">{{__('messages.discount_coupons.active_inactive')}}</th>
                        <th class="no-order \">{{__('messages.discount_coupons.action')}}</th>
                        <th class="no-order search not-visible">Active/Inactive</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $key=>$coupon)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{\Carbon\Carbon::parse($coupon->effective_date)->format('Y-m-d')}}</td>
                        <td>{{$coupon->coupon_code}}</td>
                        <td>{{$coupon->line_of_business->name}}</td>
                        <td>{{$coupon->insurance_company->company_name ?? ''}}</td>
                        <td>{{$coupon->percentage}}</td>
                        <td>
                            <div class="switch">
                                <input type="checkbox" class="toggleSwitch" id="toggleSwitch_{{$coupon->id}}" 
                                    data-id="{{$coupon->id}}" 
                                    {{$coupon->status == 1 ? 'checked' : ''}} 
                                    {{is_admin_authorized('coupons.change_status') ? '' : 'disabled'}}>
                                <label for="toggleSwitch_{{$coupon->id}}" class="slider"></label>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                @if(is_admin_authorized('coupons.view'))
                                    <a href="{{route('coupons.view',$coupon->id)}}" type="button" class="btn p-0 m-0 btn-custom viewbtn">
                                        <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                    </a>
                                @endif
                                @if(is_admin_authorized('coupons.edit'))
                                    <a href="{{route('coupons.edit',$coupon->id)}}" class="btn p-0 m-0" title="Edit">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </a>
                                @endif
                                @if(is_admin_authorized('coupons.delete_coupon'))
                                    <button class="btn p-0 m-0 deletebtn" value="{{$coupon->id}}" title="Delete">
                                        <img src="{{asset('img/icon-delete.png')}}" alt="">
                                    </button>
                                @endif
                            </div>
                            {{--
                                                                                        <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <button type="button" class="btn p-0 m-0 btn-custom viewbtn" value="{{$agentsdata->id}}">
                                                                    <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                                                </button>
                                                                <button  class="btn p-0 m-0 editbtn" value="{{$agentsdata->id}}" title="Edit">
                                                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                                                </button>
                                                            </div>
                                                        --}}
                        </td>
                        <td>{{$coupon->status == 1 ? 'Active' : 'Inactive'}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
    <div class="p-3 col-12 col-lg-8 mx-auto">
        <div class="toast align-items-center border-0 col-12 col-lg-8 w-100" style="background-color: #104E9E; color: white" data-bs-delay="3000" role="alert" aria-live="assertive" aria-atomic="true" id="clientSelectToast">
            <div class="d-flex">
                <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.discount_coupons.delete_coupon')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('coupons.delete')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Coupon ?</h4>
                                    <input type="hidden" id="deleting_id" name="coupon_id">
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">YES DELETE </button>
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

@endsection

@section('script')
    <script>
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('change', '.toggleSwitch', function() {
                let checkbox = $(this);
                let id = checkbox.data('id');
                let status = checkbox.is(':checked') ? 1 : 0;

                changeStatus(id, status, checkbox);
            });
        });
        function changeStatus(id, status, checkbox) {
            let baseUrl = window.location.origin;
            let url = baseUrl + '/admin/coupon-change-status';

            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: { 'id': id, 'status': status, "_token": csrf_token },
                success: function(data) {
                    $('.toast-body').html('Status changed successfully');
                    const toastLiveExample = document.getElementById('clientSelectToast');
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                },
                error: function() {
                    checkbox.prop('checked', !checkbox.is(':checked'));

                    $('.toast-body').html('Something went wrong. Please try again.');
                    const toastLiveExample = document.getElementById('clientSelectToast');
                    const toast = new bootstrap.Toast(toastLiveExample);
                    toast.show();
                }
            });
        }
    </script>
    <script src="{{asset('js/discount_coupon.js')}}"></script>
@endsection
