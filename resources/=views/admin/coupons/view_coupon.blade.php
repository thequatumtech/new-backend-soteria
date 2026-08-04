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
            <div class="text-white group-title fw-bold">
                {{__('messages.discount_coupons.coupon')}}
            </div>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <div class="row p-3" style="color: #92959A;">
                <div class="container">
                    <div class="row">
                        <p><strong>{{__('messages.discount_coupons.coupon_code')}} : </strong><span>{{$coupon->coupon_code}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.insurance_company')}} : </strong><span>{{$insurance_company->company_name}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.insurance_type')}} : </strong><span>{{$line_of_business->name}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.percentage')}} : </strong><span>{{$coupon->percentage}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.effective_date')}} : </strong><span>{{\Carbon\Carbon::parse($coupon->effective_date)->format('d-m-Y')}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.expiry_date')}} : </strong><span>{{\Carbon\Carbon::parse($coupon->expiry_date)->format('d-m-Y')}}</span></p>
                        <p><strong>{{__('messages.discount_coupons.coupon_message')}} : </strong><span>@if(!empty($coupon->description)) {!! $coupon->description !!} @else - @endif </span></p>
                        <p><strong>{{__('messages.discount_coupons.attachment')}} : </strong>
                            <span>
                                @if(!empty($coupon->attachment))
                                    <a href="{{asset('uploads/discount_coupons').'/'.$coupon->id.'/'.$coupon->attachment}}" target="_blank">
                                        View
                                    </a>
                                @else
                                    -
                                @endif
                            </span>
                        </p>
                    </div>
                    <div class="row pt-5">
                        @if(is_admin_authorized('coupons.send_coupon'))
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="button" class="btn rounded-1 w-100 text-white opacity-50 p-2 send_coupon_to_email-btn" style="background-color: #EF7C00;">{{__('messages.discount_coupons.send_coupon_to_email')}}</button>
                                </div>
                            </div>
                        @endif
{{--
                        <div class="col-12 col-lg-3">
                            <div class="pt-4 " style="border: none;">
                                <button type="button" class="btn rounded-1 w-100 text-white opacity-50 p-2 send_coupon_to_mobile-btn" style="background-color: #EF7C00;">{{__('messages.discount_coupons.send_coupon_to_mobile')}}</button>
                            </div>
                        </div>
--}}
                        <div class="col-12 col-lg-3">
                            <div class="pt-4 " style="border: none;">
                                <a href="{{route('coupons')}}" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{ __('messages.discount_coupons.back') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<form id="add_coupon" action="{{route('coupons.send')}}" method="post">
    @csrf
    <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
    <input type="hidden" name="send_coupon_to" id="send_coupon_to">
    <input type="hidden" name="selected_clients" id="selected_clients">
</form>
<div class="modal fade " id="ClientModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.discount_coupons.send_coupon_to')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>
                        <th>{{__('messages.clients.full_name')}}</th>
                        <th>{{__('messages.clients.birth_date')}}</th>
                        <th>{{__('messages.clients.mobile_no')}}</th>
                        <th>{{__('messages.clients.national_id_number')}}</th>
                        <th>{{__('messages.clients.gender')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.select')}}<br/>
{{--                            <input class="form-check-input select-all-checkbox" type="checkbox">--}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $key=> $data)
                        <tr>
                            <td>{{$data->full_name}}</td>
                            <td>{{\Carbon\Carbon::parse($data->birth_date)->format('d-m-Y')}}</td>
                            <td>{{$data->mobile_no}}</td>
                            <td>{{$data->national_id_number}}</td>
                            <td>{{$data->gender == 1 ? __('messages.clients.male') : __('messages.clients.female')}}</td>
                            <td><input class="form-check-input select-checkbox" type="checkbox"
                                       data-client_id="{{$data->id}}" data-client_name="{{$data->full_name}}"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary send-btn">{{__('messages.discount_coupons.send')}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('messages.discount_coupons.close')}}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/discount_coupon.js')}}"></script>
@endsection

