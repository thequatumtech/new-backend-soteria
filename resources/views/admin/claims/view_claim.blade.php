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
    /*Chatbox styles*/
    .chat-box {
        width: 100%;
        height: 300px;
        border: 1px solid #ccc;
        display: flex;
        flex-direction: column;
    }

    .chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .chat-message {
        margin-bottom: 10px;
    }

    .chat-input {
        display: flex;
        padding: 10px;
    }

    .chat-input input {
        flex-grow: 1;
        padding: 5px;
    }

    .chat-input button {
        padding: 5px 10px;
    }
    .chat-message {
        display: flex;
        align-items: flex-start;
    }

    .chat-message .time {
        margin-right: 10px;
        font-size: 12px;
        color: #888;
    }
    #file-label {
        cursor: pointer;
        margin-right: 10px;
    }

</style>
@endsection

@section('content')
        <main class="flex-grow-1 pt-5">
            <div class="container-fluid">
                <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                    <div class="text-white group-title fw-bold">
                        {{__('messages.claims.manage_claim')}}
                    </div>
                </div>
                <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <p><strong>{{__('messages.claims.claim_no')}} : </strong><span>{{$claim->claim_no}}</span></p>
                                <p><strong>{{__('messages.claims.client_name')}} : </strong><span>{{$claim->client->full_name}}</span></p>
                                <p><strong>{{__('messages.claims.policy_type')}} : </strong><span>{{__('messages.policy_types.' . $claim->policy_type)}}</span></p>
                                <p><strong>{{__('messages.claims.insurance_company')}} : </strong><span>{{$claim->insurance_company ? $claim->insurance_company->company_name : ''}}</span></p>
                                <p><strong>{{__('messages.claims.effective_date')}} : </strong><span>{{\Carbon\Carbon::parse($claim->effective_date)->format('d/m/Y')}}</span></p>
                                <p><strong>{{__('messages.claims.expiry_date')}} : </strong><span>{{\Carbon\Carbon::parse($claim->expiry_date)->format('d/m/Y')}}</span></p>
                                <p><strong>{{__('messages.claims.insurance_limit')}} : </strong><span>{{$purchasepolicy->policy_plan_limit }}</span></p>
                                <p><strong>{{__('messages.claims.policy_premium')}} : </strong><span>{{$purchasepolicy->net_premium }}</span></p>
                                <p><strong>{{__('messages.claims.no_of_claims')}} : </strong><span>{{$no_of_claims}}</span></p>
                                <p><strong>{{__('messages.claims.claim_status')}} : </strong><span>
                                        <select id="claim_status" data-claim_id="{{$claim->id}}" {{is_admin_authorized('claims.change_status') ? '' : 'disabled'}}>
                                            @foreach($claim_statuses as $single)
                                                <option value="{{$single->id}}" {{$single->name == $claim->status ? 'selected' : ''}}>{{$single->name}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </p>
                                <div class="row">
                                    <div class="col-lg-5 col-12">
                                        <p><strong>{{__('messages.claims.claim_comment')}} : </strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-7 col-12">
                                        <span>{!! $claim->claim_note ?: '-' !!}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <p><strong>{{__('messages.claims.attachments')}} : </strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-10 col-12">
                                        @forelse($attachments as $single)
                                            <div class="span_{{$single}}">
                                                <a href="{{asset('uploads/claims') . '/' . $claim->id . '/' . $single}}" target="_blank">{{$single}}</a>
                                            </div>
                                        @empty -
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-2">
                                <h3><strong>{{__('messages.claims.chatbox')}} </strong></h3>
                                <div class="chat-box">
                                    <div class="chat-messages" id="chat-messages">
                                    </div>
                                    <div class="chat-input">
                                        <input type="text" id="chat-input" placeholder="Type your message">
                                        <button id="send-button">Send</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <a href="{{ route('purchase-policy.show', $purchasepolicy->id) }}" class="btn rounded-1 w-100 text-white opacity-50 p-2 " style="background-color: #EF7C00;">{{__('messages.claims.view_policy')}} </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <a href="{{route('coupons')}}" class="btn rounded-1 w-100 text-white opacity-50 p-2 " style="background-color: #EF7C00;">{{__('messages.claims.send_discount_coupon')}} </a>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                    <button type="button" id="notify-company-btn" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                        style="background-color: #EF7C00;">
                                        {{__('messages.claims.notify_company')}}
                                    </button>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button type="button" id="notify-client-btn" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                            style="background-color: #EF7C00;">
                                            {{__('messages.claims.notify_client')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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


    <div class="modal fade" id="emailPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Email Preview</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="border rounded p-4 bg-white">

                    <div class="mb-4 p-3 border rounded bg-light">
    <div class="mb-2">
        <strong>To:</strong>

        <span id="preview-email-to">
            {{ $claim->insurance_company?->email ?? '' }}
        </span>
    </div>

    <div>
        <strong>Subject:</strong>
        <span id="preview-email-subject">
            Claim Notification - {{ $claim->claim_no }}
        </span>
    </div>
</div>

                        <h4 class="mb-4">
                            Claim Details
                        </h4>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <strong>Claim No. :</strong>
                                <span>{{ $claim->claim_no }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Client Name :</strong>
                                <span>{{ $claim->client->full_name }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Policy Type :</strong>
                                <span>{{ __('messages.policy_types.' . $claim->policy_type) }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Insurance Company :</strong>
                                <span>
                                    {{ $claim->insurance_company?->company_name ?? '' }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Effective Date :</strong>
                                <span>
                                    {{ \Carbon\Carbon::parse($claim->effective_date)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Expiry Date :</strong>
                                <span>
                                    {{ \Carbon\Carbon::parse($claim->expiry_date)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Insurance Limit :</strong>
                                <span>{{ $purchasepolicy->policy_plan_limit }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Policy Premium :</strong>
                                <span>{{ $purchasepolicy->net_premium }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Number of Claims for Client/User :</strong>
                                <span>{{ $no_of_claims }}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Claim Status :</strong>
                                <span>{{ $claim->status }}</span>
                            </div>

                            <div class="col-12 mb-3">
                                <strong>Client / User Comment / Details of the Claim. :</strong>

                                <div class="mt-2 border rounded p-3">
                                    {!! $claim->claim_note ?: '-' !!}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" id="send-preview-email-btn" class="btn text-white"
                        style="background-color: #EF7C00;">
                        Send Email
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
            <script>


                var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
                var csrf_token = "{{ csrf_token() }}";
                var claim_id = "{{$claim->id}}";



                $('#notify-company-btn').on('click', function () {
            notificationType = 'insurance_company';

            $('#preview-email-to').text(
                '{{ $claim->insurance_company?->email ?? '' }}'
            );

            $('#preview-email-subject').text(
                'Claim Notification - {{ $claim->claim_no }}'
            );

            const modal = new bootstrap.Modal(
                document.getElementById('emailPreviewModal')
            );

            modal.show();
        });

        $('#notify-client-btn').on('click', function () {
            notificationType = 'client';

            $('#preview-email-to').text(
                '{{ $client->email_id ?? '' }}'
            );

            $('#preview-email-subject').text(
                'Claim Update - {{ $claim->claim_no }}'
            );

            const modal = new bootstrap.Modal(
                document.getElementById('emailPreviewModal')
            );

            modal.show();
        });


        let notificationType = null;

        $('#send-preview-email-btn').on('click', function () {
            const button = $(this);

            // Prevent multiple clicks
            if (button.prop('disabled')) {
                return;
            }

            // Show loader
            button.prop('disabled', true);
            button.html(`
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Sending...
        `);

            $.ajax({
                type: 'POST',
                url: window.location.origin + '/admin/claim-send-notification',
                dataType: 'json',
                data: {
                    claim_id: claim_id,
                    type: notificationType,
                    _token: csrf_token
                },
                success: function (data) {
                    const modalElement = document.getElementById('emailPreviewModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);

                    if (modal) {
                        modal.hide();
                    }

                    $('.toast-body').html(data.message);

                    const toast = new bootstrap.Toast(
                        document.getElementById('clientSelectToast')
                    );

                    toast.show();
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Unable to send email.';

                    $('.toast-body').html(message);

                    const toast = new bootstrap.Toast(
                        document.getElementById('clientSelectToast')
                    );

                    toast.show();
                },
                complete: function () {
                    // Restore button
                    button.prop('disabled', false);
                    button.html('Send Email');
                }
            });
        });
            </script>

    <script>


        var sendMessageUrl = "{{ route('claims.send_message') }}";
      
    </script>

            <script src="{{asset('js/claims.js?v=1')}}"></script>
@endsection

