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
                @if(isset($coupon))
                    {{__('messages.discount_coupons.edit_coupon')}}
                @else
                {{__('messages.discount_coupons.add_coupon')}}
                    @endif
            </div>
        </div>
        <form action="{{route('coupons.save')}}" id="add_coupon" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="coupon_id" value="{{isset($coupon)?$coupon->id:null}}">
            <input type="hidden" id="form_type" name="form_type" value="{{isset($coupon)?'edit':'add'}}">
            <input type="hidden" name="send_coupon_to" id="send_coupon_to" >
            <input type="hidden" name="selected_clients" id="selected_clients">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.coupon_code')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="coupon_code" style="border: none;" required value="{{$coupon_code}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.insurance_company')}}</div>
                                <select name="insurance_company_id" id="insurance_company_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('insurance_company_id') && !isset($coupon->insurance_company_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @foreach($insurance_companies as $single)
                                        <option value="{{$single->id}}" {{(old('insurance_company_id') && old('insurance_company_id') == $single->id) ? 'selected':(isset($coupon->insurance_company_id) && $coupon->insurance_company_id == $single->id? 'selected':'')}}>{{$single->company_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.insurance_type')}}</div>
                                <select name="line_of_business_id" id="line_of_business_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('line_of_business_id') && !isset($coupon->line_of_business_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @if(isset($line_of_businesses))
                                        @foreach($line_of_businesses as $single)
                                            <option value="{{$single->id}}" {{(old('line_of_business_id') && old('line_of_business_id') == $single->id) ? 'selected':(isset($coupon->line_of_business_id) && $coupon->line_of_business_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.percentage')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="percentage" style="border: none;" required value="{{old('percentage',$coupon->percentage??0)}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.effective_date')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="effective_date" id="effective_date" min="{{\Carbon\Carbon::today()->format('Y-m-d')}}" style="border: none; font-weight: 330;" required  value="{{old('effective_date')?:($coupon->effective_date ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.discount_coupons.expiry_date')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="expiry_date" id="expiry_date" style="border: none; font-weight: 330;" required value="{{old('expiry_date')?:($coupon->expiry_date ?? '')}}">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.discount_coupons.coupon_message')}}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    {{__('messages.discount_coupons.add_message')}}
                                </div>
                                <textarea name="description" class="ceditor" id="description">{{isset($coupon) && !empty($coupon->description) ? $coupon->description : ''}}</textarea>
                            </div>
                        </div>
                        <div class="row pt-4 g-0 gap-4">
                            <div class="col-12 col-lg">
                                <div> {{__('messages.discount_coupons.add_attachment')}} </div>
                                @if(isset($coupon) && $coupon->attachment)
                                    <a id="coupon_attachment" href="{{asset('uploads/discount_coupons').'/'.$coupon->id.'/'.$coupon->attachment}}"
                                       target="_blank">View</a>
                                @endif
                                <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                    <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                        <div>
                                            <img src="{{asset('img/icon-upload.png')}}" alt="Upload">
                                        </div>
                                        <input type="file" name="attachment" id="attachment" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
                                        <div class="file-margin">
                                            <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                            <label id="attachment-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
{{--
                        <div class="row pt-5">
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="button" class="btn btn-warning rounded-1 w-100 text-white opacity-50 p-2 select-client" >{{ __('messages.discount_coupons.select_client')}} </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <label class="form-check-label">
                                    {{__('messages.discount_coupons.send_coupon_to')}}
                                </label>
                                <div class="radio-class">
                                    <input class="form-check-input" type="radio"
                                           name="send_to" id="send_to_email" value="1"
                                    @if((old('send_to') && (old('send_to') == 1)) || (isset($coupon->send_to) && $coupon->send_to == 1))
                                        {{'checked'}}
                                        @endif
                                    >
                                    <label class="form-check-label radio-label" for="send_to_email">
                                        {{ __('messages.discount_coupons.email') }}
                                    </label>

                                    <input class="form-check-input" type="radio"
                                           name="send_to" id="send_to_mobile" value="1"
                                    @if((old('send_to') && (old('send_to') == 2)) || (isset($coupon->send_to) && $coupon->send_to == 2))
                                        {{'checked'}}
                                        @endif
                                    >
                                    <label class="form-check-label radio-label" for="send_to_mobile">
                                        {{ __('messages.discount_coupons.mobile') }}
                                    </label>
                                </div>
                            </div>
                        </div>
--}}
                        <div class="row pt-5">
{{--                            <div class="col-12 col-lg-9"></div>--}}
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="button" class="btn rounded-1 w-100 text-white opacity-50 p-2 send_coupon_to_email-btn" style="background-color: #EF7C00;">{{__('messages.discount_coupons.send_coupon_to_email')}}</button>
                                </div>
                            </div>
{{--
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="button" class="btn rounded-1 w-100 text-white opacity-50 p-2 send_coupon_to_mobile-btn" style="background-color: #EF7C00;">{{__('messages.discount_coupons.send_coupon_to_mobile')}}</button>
                                </div>
                            </div>
--}}
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{isset($coupon)? __('messages.clients.save') : __('messages.clients.add')}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

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

