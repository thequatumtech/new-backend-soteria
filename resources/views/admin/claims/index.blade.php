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
            <div class="text-white group-title fw-bold">{{__('messages.claims.claims_list')}}</div>
{{--
            <a href="{{route('claims.add')}}" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </a>
--}}
        </div>
        @if(is_admin_authorized('claims.view'))
            <div class="col-12 col-lg-12">
                <div class="pt-4 row" style="border: none;">
                    <div class="col-lg-3">
                        <button type="button" id="manage_claim_btn" data-route="{{route('claims.view')}}" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                style="background-color: #EF7C00;">{{__('messages.claims.manage_claim')}}
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>{{__('messages.claims.client_name')}}</th>
                        <th>{{__('messages.claims.policy_type')}}</th>
                        <th>{{__('messages.claims.expiry_date')}}</th>
                        <th>{{__('messages.claims.claim_no')}}</th>
                        <th>{{__('messages.claims.insurance_company')}}</th>
                        <th class="no-order no-search">{{__('messages.claims.status')}}</th>
                        <th class="no-order no-search">{{__('messages.claims.view')}}</th>
                        <th class="no-order no-search">{{__('messages.claims.select')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($claims as $single)
                    <tr>
                        <td>{{ $single->client->full_name }}</td>
                        <td>{{ __('messages.policy_types.'.$single->policy_type) }}</td>
                        <td>{{\Carbon\Carbon::parse($single->expiry_date)->format('d/m/Y')}}</td>
                        <td>{{$single->claim_no}}</td>
                        <td>{{$single->insurance_company_id?$single->insurance_company->company_name:''}}</td>
                        <td>{{$single->status}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <a href="javascript:void(0);" type="button" class="btn p-0 m-0 btn-custom viewbtn">
                                    <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                </a>
{{--
                                <a href="{{route('complaints.edit',$single->id)}}" class="btn p-0 m-0" title="Edit">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </a>
--}}
                            </div>
                        </td>
                        <td><input class="form-check-input select-checkbox" type="checkbox" data-claim_id="{{$single->id}}"></td>
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

@endsection

@section('script')
    <script>
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/claims.js')}}"></script>
@endsection
