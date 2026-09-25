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
            <div class="text-white group-title fw-bold">{{__('messages.complaints.complaints_list')}}</div>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>{{__('messages.complaints.complaint_no')}}</th>
                        <th>{{__('messages.complaints.date')}}</th>
                        <th>{{__('messages.complaints.client_name')}}</th>
                        <th>{{__('messages.complaints.insurance_type')}}</th>
                        <th>{{__('messages.complaints.insurance_company')}}</th>
                        <th>{{__('messages.complaints.mobile_no')}}</th>
                        <th class="no-order no-search">{{__('messages.complaints.status')}}</th>
                        <th class="no-order no-search">{{__('messages.complaints.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $single)
                    <tr>
                        <td>{{ $single->complaint_number }}</td>
                        <td>{{\Carbon\Carbon::parse($single->complaint_date)->format('d/m/Y')}}</td>
                        <td>{{$single->client->full_name}}</td>
                        <td>{{$single->line_of_business->name ?? '-'}}</td>
                        <td>{{ optional($single->insurance_company)->company_name ?? '' }}</td>
                        <td>{{$single->client->mobile_no}}</td>
                        <td>{{ $single->status->name }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                @if(is_admin_authorized('complaints.view'))
                                <a href="{{route('complaints.view',$single->id)}}" type="button" class="btn p-0 m-0 btn-custom viewbtn">
                                    <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                </a>
                                @endif
                                @if(is_admin_authorized('complaints.edit'))
                                <a href="{{route('complaints.edit',$single->id)}}" class="btn p-0 m-0" title="Edit">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </a>
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
    <script src="{{asset('js/complaints.js')}}"></script>
@endsection
