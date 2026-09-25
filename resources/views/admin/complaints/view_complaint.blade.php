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
                                <p><strong>{{__('messages.complaints.insurance_company')}} : </strong><span>{{$complaint->insurance_company ? $complaint->insurance_company->company_name : ''}}</span></p>
                                <p><strong>{{__('messages.complaints.insurance_type')}} : </strong><span>{{$complaint->line_of_business_id ? $complaint->line_of_business->name : ''}}</span></p>
                                <p><strong>{{__('messages.complaints.complaint_date')}} : </strong><span>{{\Carbon\Carbon::parse($complaint->effective_date)->format('d/m/Y')}}</span></p>
                                <p><strong>{{__('messages.complaints.complaint_status')}} : </strong><span>{{$complaint->status->name}}</span></p>
                                <div class="row">
                                    <div class="col-lg-3 col-12">
                                        <p><strong>Complaints Message : </strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-9 col-12">
                                        <span>{!! $complaint->complaint_message ?: '-' !!}</span>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-12">
                                        <p><strong>{{__('messages.complaints.complaint_log')}} : </strong>
                                        </p>
                                    </div>
                                    <div class="col-lg-10 col-12">
                                        <span>{!! $complaint->complaint_log ?: '-' !!}</span>
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
                                                <a href="{{asset('uploads/complaints') . '/' . $complaint->id . '/' . $single}}" target="_blank">{{$single}}</a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-6"></div>
                                <div class="col-12 col-lg-3">
                                       <!-- @if(canAccessRoute('complaint_emails'))
                                    <div class="pt-4 " style="border: none;">
                                        <button class="btn rounded-1 w-100 text-white opacity-50 p-2 send_to_email_btn" style="background-color: #EF7C00;">{{__('messages.complaints.send_to_email')}} </button>
                                    </div>
                                    @endif -->

                                    @if(canAccessRoute('complaint_emails'))
        <div class="pt-4">
            <button
                type="button"
                id="send_complaint_email_btn"
                class="btn rounded-1 w-100 text-white opacity-50 p-2"
                style="background-color: #EF7C00;">
                {{ __('messages.complaints.send_to_email') }}
            </button>
        </div>
    @endif
                                </div>
                                <div class="col-12 col-lg-3">
                                        @if(canAccessRoute('complaints.edit'))
                                    <div class="pt-4 " style="border: none;">
                                        <a href="{{route('complaints.edit', $complaint->id)}}" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{__('messages.complaints.edit')}} </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>


        <div class="modal fade" id="ComplaintMailPreviewModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">

                <div class="modal-header text-white" style="background-color:#104E9E;">
                    <h5 class="modal-title">Send Complaint Email</h5>

                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <img src="{{ asset('img/icon-close.svg') }}" alt="">
                        </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">To Email</label>

                        <input type="email" id="complaint_email" class="form-control"
                            value="{{ $complaint->client?->email_id ?? '' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject</label>

                        <input type="text" id="complaint_email_subject" class="form-control"
                            value="Complaint {{ $complaint->complaint_number }}">
                    </div>

                    <div>
                        <label class="form-label fw-semibold">Email Content</label>

                        <textarea name="complaint_mail_content" class="ceditornwq"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" id="send_complaint_mail_btn" class="btn text-white px-4"
                        style="background-color:#104E9E;">
                        Send Mail
                    </button>
                </div>

            </div>
        </div>
        </div>
@endsection

@section('script')

    <script>
        let complaintMailEditor = null;

        document.addEventListener('DOMContentLoaded', function () {

            // Initialize CKEditor using .ceditor class
            document.querySelectorAll('.ceditornwq').forEach(function (element) {

                CKEDITOR.ClassicEditor.create(element, {
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'strikethrough',
                            '|',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'alignment',
                            '|',
                            'link',
                            'insertTable',
                            '|',
                            'undo',
                            'redo'
                        ],
                        shouldNotGroupWhenFull: true
                    },

                    heading: {
                        options: [
                            {
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            }
                        ]
                    },

                    removePlugins: [
                        'CKBox',
                        'CKFinder',
                        'EasyImage',
                        'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory',
                        'PresenceList',
                        'Comments',
                        'TrackChanges',
                        'TrackChangesData',
                        'RevisionHistory',
                        'Pagination',
                        'WProofreader',
                        'MathType'
                    ]

                }).then(function (editor) {

                    complaintMailEditor = editor;

                    window.ckEditors = window.ckEditors || {};
                    window.ckEditors['complaint_mail_content'] = editor;

                }).catch(function (error) {
                    console.error('CKEditor initialization error:', error);
                });

            });


            // Open email modal
            const openEmailButton = document.getElementById('send_complaint_email_btn');

            if (openEmailButton) {

                openEmailButton.addEventListener('click', function () {

                    const email = document.getElementById('complaint_email').value;

                    if (!email) {
                        alert('Client email not found.');
                        return;
                    }

                    // Existing complaint content
                    const content = `
                        <p>
                            <strong>Complaint Message:</strong>
                        </p>

                        <p>
                            {!! nl2br(e($complaint->complaint_message ?? '-')) !!}
                        </p>

                        <p>
                            <strong>Complaint Log:</strong>
                        </p>

                        <p>
                            {!! nl2br(e($complaint->complaint_log ?? '-')) !!}
                        </p>
                    `;

                    // Put existing content into CKEditor
                    if (complaintMailEditor) {
                        complaintMailEditor.setData(content);
                    }

                    const modalElement = document.getElementById(
                        'ComplaintMailPreviewModal'
                    );

                    const modal = bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );

                    modal.show();
                });
            }


            // Send email
            const sendEmailButton = document.getElementById(
                'send_complaint_mail_btn'
            );

            if (sendEmailButton) {

                sendEmailButton.addEventListener('click', function () {

                    const subject = document.getElementById(
                        'complaint_email_subject'
                    ).value;

                    const content = complaintMailEditor
                        ? complaintMailEditor.getData()
                        : '';


                    if (!subject.trim()) {
                        alert('Email subject is required.');
                        return;
                    }

                    if (!content.trim()) {
                        alert('Email content cannot be empty.');
                        return;
                    }


                    this.disabled = true;
                    this.textContent = 'Sending...';


                    fetch("{{ route('complaint_emails.send') }}", {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },

                        body: JSON.stringify({

                            complaint_id: {{ $complaint->id }},

                            subject: subject,

                            content: content

                        })

                    })

                        .then(function (response) {

                            return response.json();

                        })

                        .then(function (data) {

                            if (data.success || data.status === 'success') {

                                const modalElement = document.getElementById(
                                    'ComplaintMailPreviewModal'
                                );

                                const modal = bootstrap.Modal.getInstance(
                                    modalElement
                                );

                                if (modal) {
                                    modal.hide();
                                }

                                alert(
                                    data.message ||
                                    'Email sent successfully.'
                                );

                            } else {

                                alert(
                                    data.message ||
                                    'Failed to send email.'
                                );

                            }

                        })

                        .catch(function (error) {

                            console.error(
                                'Email sending error:',
                                error
                            );

                            alert('Error sending email.');

                        })

                        .finally(function () {

                            this.disabled = false;
                            this.textContent = 'Send Mail';

                        });

                });
            }

        });
    </script>



    <script>
        var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/discount_coupon.js')}}"></script>
@endsection



