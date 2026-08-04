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
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">
                {{__('messages.complaints.edit_complaint')}}
            </div>
        </div>
        <form action="{{route('complaints.save')}}" id="add_coupon" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="complaint_id" value="{{$complaint->id}}">
            <input type="hidden" name="deleted_attachments" id="deleted_attachments">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.complaints.complaint_number')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" style="border: none;" value="{{$complaint->complaint_number}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.complaints.insurance_company')}}</div>
                                <select name="insurance_company_id" id="insurance_company_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('insurance_company_id') && !isset($complaint->insurance_company_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @foreach($insurance_companies as $single)
                                        <option value="{{$single->id}}" {{(old('insurance_company_id') && old('insurance_company_id') == $single->id) ? 'selected':(isset($complaint->insurance_company_id) && $complaint->insurance_company_id == $single->id? 'selected':'')}}>{{$single->company_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.complaints.insurance_type')}}</div>
                                <select name="line_of_business_id" id="line_of_business_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('line_of_business_id') && !isset($complaint->line_of_business_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @if(isset($line_of_businesses))
                                        @foreach($line_of_businesses as $single)
                                            <option value="{{$single->id}}" {{(old('line_of_business_id') && old('line_of_business_id') == $single->id) ? 'selected':(isset($complaint->line_of_business_id) && $complaint->line_of_business_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.complaints.complaint_date')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="date" name="complaint_date" readonly style="border: none; font-weight: 330;" value="{{old('complaint_date')?:($complaint->complaint_date ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.complaints.complaint_status')}}</div>
                                <select name="complaint_status_id" id="complaint_status_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('complaint_status_id') && !isset($complaint->complaint_status_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @if(isset($complaint_statuses))
                                        @foreach($complaint_statuses as $single)
                                            <option value="{{$single->id}}" {{(old('complaint_status_id') && old('complaint_status_id') == $single->id) ? 'selected':(isset($complaint->complaint_status_id) && $complaint->complaint_status_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.complaints.complaint_log')}}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    {{__('messages.complaints.add_log')}}
                                </div>
                                <textarea name="complaint_log" id="complaint_log">{{isset($complaint) && !empty($complaint->complaint_log) ? $complaint->complaint_log : ''}}</textarea>
                            </div>
                        </div>

                        <div class="row pt-4 g-0">
                            <div class="col-lg-2 col-12">
                                <p><strong>{{__('messages.complaints.attachments')}} : </strong>
                                </p>
                            </div>
                            <div class="col-lg-10 col-12">
                                @foreach($attachments as $key => $single)
                                    <div class="span_{{$key}}">
                                        <a href="{{asset('uploads/complaints') . '/'. $complaint->id . '/' . $single}}" target="_blank">{{$single}}</a>
                                        <button class="btn btn-sm delete-attachment-btn" type="button"
                                                data-id="{{$key}}" data-name="{{$single}}">
                                            <img src="{{asset('img/icon-delete.png')}}" style="color:black;" alt="">
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row pt-4 g-0">
                            <div class="col-lg-2 col-12">
                                {{__('messages.complaints.add_attachments')}} :
                            </div>
                            <div class="col-lg-10 col-12">
                                <input type="file" class="form-control" name="attachments[]" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple />
                            </div>
                        </div>
                        <div class="row pt-5">
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{__('messages.clients.save')}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@endsection

@section('script')
    <script>
        var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/complaints.js')}}"></script>
@endsection

