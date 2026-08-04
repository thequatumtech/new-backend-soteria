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
                <div class="text-white group-title fw-bold">{{__('messages.black_lists.black_list_file')}}</div>
            </div>
            <form id="edit_client" method="post" action="{{route('save_black_list_client')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <input type="hidden" name="deleted_attachments" id="deleted_attachments">
                <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                    <p><strong>{{__('messages.black_lists.name')}} : </strong><span>{{$client->full_name}}</span></p>
                    <p><strong>{{__('messages.black_lists.client_mobile')}} : </strong><span>{{$client->mobile_no}}</span></p>
                    <p><strong>{{__('messages.black_lists.client_email')}} : </strong><span>{{$client->email_id}}</span></p>
                    <p><strong>{{__('messages.black_lists.client_national_id')}} : </strong><span>{{$client->national_id_number}}</span></p>
                    <p><strong>{{__('messages.black_lists.insurance_company_list')}} : </strong><span>{{$insurance_companies_name?:'-'}}</span></p>
                    <p><strong>{{__('messages.black_lists.insurance_type_list')}} : </strong><span>{{$insurance_types_name?:'-'}}</span></p>
                    <p><strong>{{__('messages.black_lists.gross_premium_paid')}} : </strong><span>{{$client->black_list_detail->total_gross_premium_paid}}</span></p>
                    <p><strong>{{__('messages.black_lists.net_premium_paid')}} : </strong><span>{{$client->black_list_detail->total_net_premium_paid}}</span></p>
                    <p><strong>{{__('messages.black_lists.insured_period')}} : </strong><span>{{$insured_period}}</span></p>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <p><strong>{{__('messages.black_lists.insurance_type_cannot_purchase')}} : </strong></p>
                        </div>
                        <div class="col-lg-6 col-12">
                            @foreach($line_of_business as $lob)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        {{ $lob->name }}
                                    </label>
                                    <input class="form-check-input checkBusiness" type="checkbox"
                                           name="line_of_business[]"
                                           id="lob{{ $lob->id }}" value="{{ $lob->id }}" @if(in_array($lob->id,$blocked_insurance_types_arr)) checked @endif>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p><strong>{{__('messages.black_lists.reason')}} : </strong><span><textarea name="black_list_reason" class="ceditor">{!! $client->black_list_reason !!}</textarea></span></p>

                    <div class="row">
                        <div class="col-lg-2 col-12">
                            <p><strong>{{__('messages.black_lists.attachments')}} : </strong>
                            </p>
                        </div>
                        <div class="col-lg-10 col-12">
                            @foreach($attachments as $key => $single)
                                <div class="span_{{$key}}">
                                    <a href="{{asset('uploads/black_list') . '/'. $client->id . '/' . $single}}" target="_blank">{{$single}}</a>
                                    <button class="btn btn-sm delete-attachment-btn" type="button"
                                            data-id="{{$key}}" data-name="{{$single}}">
                                        <img src="{{asset('img/icon-delete.png')}}" style="color:black;" alt="">
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-12">
                            {{__('messages.black_lists.add_attachments')}} :
                        </div>
                        <div class="col-lg-10 col-12">
                            <input type="file" class="form-control" name="attachments[]" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple />
                        </div>
                    </div>
                    <div class="row pt-5">
                        <div class="col-12 col-lg-9"></div>
                        <div class="col-12 col-lg-3">
                            <div class="pt-4 " style="border: none;">
                                <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{__('messages.clients.save')}} </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </main>

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

