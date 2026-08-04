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
                {{$complaint->client->full_name . '\'s ' . __('messages.complaints.complaint_log_file')}}
            </div>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <div class="row p-3" style="color: #92959A;">
                <div class="container">
                    <div class="row">
                        <p><strong>{{__('messages.complaints.complaint_number')}} : </strong><span>{{$complaint->complaint_number}}</span></p>
                        <p><strong>{{__('messages.complaints.insurance_company')}} : </strong><span>{{$complaint->insurance_company?$complaint->insurance_company->company_name:''}}</span></p>
                        <p><strong>{{__('messages.complaints.insurance_type')}} : </strong><span>{{$complaint->line_of_business_id?$complaint->line_of_business->name:''}}</span></p>
                        <p><strong>{{__('messages.complaints.complaint_date')}} : </strong><span>{{\Carbon\Carbon::parse($complaint->effective_date)->format('d/m/Y')}}</span></p>
                        <p><strong>{{__('messages.complaints.complaint_status')}} : </strong><span>{{$complaint->status->name}}</span></p>
                        <div class="row">
                            <div class="col-lg-2 col-12">
                                <p><strong>{{__('messages.complaints.complaint_log')}} : </strong>
                                </p>
                            </div>
                            <div class="col-lg-10 col-12">
                                <span>{!! $complaint->complaint_log?:'-' !!}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-12">
                                <p><strong>{{__('messages.complaints.attachments')}} : </strong>
                                </p>
                            </div>
                            <div class="col-lg-10 col-12">
                                @foreach($attachments as $single)
                                    <div class="span_{{$single}}">
                                        <a href="{{asset('uploads/complaints') . '/'. $complaint->id . '/' . $single}}" target="_blank">{{$single}}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row pt-5">
                        <div class="col-12 col-lg-6"></div>
                        <div class="col-12 col-lg-3">
                            <div class="pt-4 " style="border: none;">
                                <button class="btn rounded-1 w-100 text-white opacity-50 p-2 send_to_email_btn" style="background-color: #EF7C00;">{{__('messages.complaints.send_to_email')}} </button>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="pt-4 " style="border: none;">
                                <a href="{{route('complaints.edit',$complaint->id)}}" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{__('messages.complaints.edit')}} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('script')
    <script>
        var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/discount_coupon.js')}}"></script>
@endsection

