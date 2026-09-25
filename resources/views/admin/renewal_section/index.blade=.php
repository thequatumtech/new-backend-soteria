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

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    input[type="file"] {
        height: 50px;
        cursor: pointer;
        margin-top: -40px;
        opacity: 0;
        position: relative;
    }

    .file-margin {
        text-align: center;
    }

    .file-margin .error {
        text-align: center;
    }

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

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }
</style>
@endsection

@section('content')


<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{$table_title}}</div>
        </div>

        <div class="col-12">
            <div class="row pt-4 pb-3 align-items-end g-3">

                @if($is_active_policies)
                <div class="col-md-3">
                    <button type="button" id="notify_btn" data-route="{{ route('notify_renewal') }}"
                        class="btn w-100 text-white p-2 renewal_section_btn"
                        style="background-color: #EF7C00;">
                        {{ __('messages.renewal_section.notify') }}
                    </button>
                </div>
                @endif
                @if ($is_active_policies && !$is_expired_policies)
                <div class="col-md-3">
                    <button type="button" id="cancel_btn" data-route="{{ route('cancel_policy') }}"
                        class="btn w-100 text-white p-2 renewal_section_btn"
                        style="background-color: #EF7C00;">
                        {{ __('messages.renewal_section.cancel') }}
                    </button>
                </div>
                @endif
                @if(auth()->user()->is_super_admin == 1)
                <div class="col-md-3">
                    <button type="button" id="renew_btn" data-route="{{ route('renew_policy') }}"
                        class="btn w-100 text-white p-2 renewal_section_btn"
                        style="background-color: #EF7C00;">
                        {{ __('messages.renewal_section.renew') }}
                    </button>
                </div>
                @endif
                <div class="col-md-6">
                    <div class="card p-3 border-0 shadow-sm">
                        <div class="mb-2">
                            <label for="search_type" class="form-label fw-semibold">{{ __('messages.renewal_section.search_type') }}</label>
                            <select id="search_type" class="form-select">
                                <option value="">{{ __('messages.renewal_section.select_criteria') }}</option>
                                <option value="mobile">{{ __('messages.renewal_section.mobile') }}</option>
                                <option value="first_name">{{ __('messages.renewal_section.first_name') }}</option>
                                <option value="last_name">{{ __('messages.renewal_section.last_name') }}</option>
                                <option value="email">{{ __('messages.renewal_section.email') }}</option>
                                <option value="national_id">{{ __('messages.renewal_section.national_id') }}</option>
                                <option value="passport">{{ __('messages.renewal_section.passport') }}</option>
                                <option value="residence">{{ __('messages.renewal_section.residence_number') }}</option>
                                <option value="policy_number">{{ __('messages.renewal_section.residence') }}</option>
                                <option value="renewal_date">{{ __('messages.renewal_section.renewal_date') }}</option>
                            </select>
                        </div>

                        <div id="search_inputs" class="row g-2">
                        </div>

                        <button type="button" id="search_btn" data-route="{{ $search_route }}"
                            class="btn mt-3 text-white w-100"
                            style="background-color: #EF7C00;">
                            {{ __('messages.renewal_section.search') }}
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 border-0 shadow-sm">

                        <div class="mb-2">
                            <label for="mail_type" class="form-label fw-semibold">
                                {{ __('messages.renewal_section.mail_type') }}
                            </label>

                            <select id="mail_type" class="form-select">
                                <option value="">{{ __('messages.renewal_section.select_mail') }}</option>
                                <option value="client">{{ __('messages.renewal_section.client_mail') }}</option>
                                <option value="agent">{{ __('messages.renewal_section.agent_mail') }}</option>
                            </select>
                        </div>

                        <button type="button"
                            id="preview_mail_btn"
                            class="btn mt-3 text-white w-100"
                            style="background-color:#EF7C00;">
                            {{ __('messages.renewal_section.preview_mail') }}
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchType = document.getElementById('search_type');
                const searchInputs = document.getElementById('search_inputs');
                const searchBtn = document.getElementById('search_btn');

                searchType.addEventListener('change', function() {
                    const value = this.value;
                    searchInputs.innerHTML = '';

                    if (value === 'renewal_date') {
                        searchInputs.innerHTML = `
                    <div class="col">
                        <input type="date" name="from_date" class="form-control" placeholder="From Date" required />
                    </div>
                    <div class="col">
                        <input type="date" name="to_date" class="form-control" placeholder="To Date" required />
                    </div>
                `;
                    } else if (value) {
                        const label = this.options[this.selectedIndex].text;
                        searchInputs.innerHTML = `
                    <div class="col-12">
                        <input type="text" name="${value}" class="form-control" placeholder="Enter ${label}" required />
                    </div>
                `;
                    }
                });

                searchBtn.addEventListener('click', function() {
                    const route = this.getAttribute('data-route');
                    const type = searchType.value;

                    if (!type) {
                        alert('Please select a search criteria.');
                        return;
                    }

                    const data = {
                        _token: '{{ csrf_token() }}'
                    };
                    const inputFields = searchInputs.querySelectorAll('input');
                    let valid = true;

                    inputFields.forEach(input => {
                        if (!input.value) {
                            input.classList.add('is-invalid');
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                            data[input.name] = input.value;
                        }
                    });

                    if (!valid) return;

                    fetch(route, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(async response => {
                            const text = await response.text();

                            try {
                                const result = JSON.parse(text);
                                const tbody = document.getElementById('policy_table_body');
                                tbody.innerHTML = '';

                                if (result.status === 'success') {
                                    if (result.policies.length === 0) {
                                        tbody.innerHTML = `<tr><td colspan="7" class="text-center">No policies found.</td></tr>`;
                                        return;
                                    }

                                    result.policies.forEach(policy => {
                                        const row = document.createElement('tr');

                                        const clientName = policy.client?.full_name ?? 'N/A';
                                        const policyType = policyTypeTranslations[policy.policy_type] ?? policy.policy_type;
                                        const expiryDate = new Date(policy.expiry_date).toLocaleDateString('en-GB');
                                        const premiumPaid = policy.gross_premium ?? 'N/A';
                                        const companyName = policy.insurance_company?.company_name ?? '';

                                        row.innerHTML = `
                                <td>${clientName}</td>
                                <td>${policyType}</td>
                                <td>${expiryDate}</td>
                                <td>${premiumPaid}</td>
                                <td>${companyName}</td>
                                <td>
                                    <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                        <a href="javascript:void(0);" type="button" class="btn p-0 m-0 btn-custom viewbtn">
                                            <img src="{{ asset('img/icon-eye.png') }}" alt="View" title="View">
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <input class="form-check-input select-checkbox" type="checkbox" data-policy_id="${policy.id}">
                                </td>
                            `;
                                        tbody.appendChild(row);
                                    });
                                } else {
                                    alert('Failed to load data. Please try again.');
                                }

                            } catch (e) {
                                console.error('Invalid JSON:', text);
                                alert('Unexpected response from server.');
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            alert('An error occurred during the search.');
                        });
                });
            });
        </script>


        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>{{__('messages.renewal_section.client_name')}}</th>
                        <th>{{__('messages.renewal_section.policy_type')}}</th>
                        <th>{{__('messages.renewal_section.expiry_date')}}</th>
                        <th>{{__('messages.renewal_section.premium_paid')}}</th>
                        <th>{{__('messages.renewal_section.insurance_company')}}</th>
                        <th class="no-order no-search">{{__('messages.renewal_section.action')}}</th>
                        {{-- <th class="no-order no-search">{{__('messages.renewal_section.select')}}</th> --}}
                         <th class="no-order no-search">
                                                       <input class="form-check-input" type="checkbox" id="select_all_checkbox" title="Select All">

                        </th>
                    </tr>
                </thead>
                <tbody id="policy_table_body">
                    @foreach($policies as $single)
                    <tr>
                        <td>{{ $single->client->full_name?? '' }}</td>
                        <td>{{ __('messages.policy_types.'.$single->policy_type) }}</td>
                        <td>{{ \Carbon\Carbon::parse($single->expiry_date)->format('d/m/Y') }}</td>
                        <td>{{ $single->gross_premium }}</td>
                        <td>{{ $single->insurance_company_id ? $single->insurance_company->company_name ?? '' : '' }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <a href="javascript:void(0);" class="btn p-0 m-0 btn-custom viewbtn {{is_admin_authorized('client.purchased_policy')?'purchased_policy':''}}" data-client_id="{{ $single->client->id ?? '' }}" title="View">
                                    <img src="{{asset('img/icon-eye.png')}}" alt="">
                                </a>
                                {{--route('client.edit',$data->id)--}}
                                <a href="javascript:void(0);" class="btn p-0 m-0" title="Edit">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </a>
                            </div>
                        </td>
                        <td>
                            <input class="form-check-input select-checkbox" type="checkbox" data-policy_id="{{ $single->id }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="selected_policy_id">
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
<div class="modal fade " id="PurchasedPolicyModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.clients.purchased_policies')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('messages.renewal_section.client_name')}}</th>
                            <th>{{__('messages.renewal_section.policy_type')}}</th>
                            <th>{{__('messages.renewal_section.expiry_date')}}</th>
                            <th>{{__('messages.renewal_section.premium_paid')}}</th>
                            <th>{{__('messages.renewal_section.insurance_company')}}</th>
                            <!-- <th scope="col">{{__('messages.clients.policy_type')}}</th> -->
                            <!-- <th scope="col">{{__('messages.clients.action')}}</th> -->
                        </tr>
                    </thead>
                    <tbody class="purchased_policy_table_body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="MailPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header text-white" style="background-color:#104E9E;">
                <h5 class="modal-title">Mail Preview</h5>
                <button type="button" class="btn" data-bs-dismiss="modal">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <div class="modal-body">
                <textarea name="mail_content" id="mail_preview_editor"></textarea>
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <button type="button" id="send_mail_btn"
                    class="btn text-white px-4"
                    style="background-color:#104E9E;">
                    Send Mail
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
<script>
    const policyTypeTranslations = @json(trans('messages.policy_types'));
    const policy_type = policyTypeTranslations;
</script>


@section('script')
<script>
    var csrf_token = "{{ csrf_token() }}";
</script>
<script>
    (function() {
        let mailPreviewEditor = null;
        let currentPolicyId = null;
        let currentMailType = null;

        document.addEventListener('DOMContentLoaded', function() {

            CKEDITOR.ClassicEditor.create(document.getElementById('mail_preview_editor'), {
                toolbar: {
                    items: [
                        'exportPDF', 'exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline',
                        'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo', '-',
                        'fontSize', 'fontFamily', 'fontColor',
                        'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'insertImage', 'blockQuote', 'insertTable',
                        'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                        'textPartLanguage', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                placeholder: 'Start typing here...',
                heading: {
                    options: [{
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
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 'default', 18, 20, 22],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [{
                        marker: '@',
                        feed: [
                            '@apple', '@bears', '@brownie', '@cake', '@candy',
                            '@chocolate', '@cookie', '@cream', '@donut',
                            '@fruitcake', '@gingerbread', '@ice', '@jelly-o',
                            '@liquorice', '@macaroon', '@marzipan',
                            '@pudding', '@sugar', '@sweet', '@wafer'
                        ],
                        minimumCharacters: 1
                    }]
                },
                removePlugins: [
                    'CKBox', 'CKFinder', 'EasyImage',
                    'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments',
                    'TrackChanges', 'TrackChangesData', 'RevisionHistory',
                    'Pagination', 'WProofreader', 'MathType'
                ]
            }).then(editor => {
                mailPreviewEditor = editor;
                window.ckEditors['mail_preview_editor'] = editor;
            }).catch(console.error);

            document.getElementById('preview_mail_btn').addEventListener('click', function() {

                const mailType = document.getElementById('mail_type').value;
                const selectedCheck = document.querySelector('.select-checkbox:checked');

                if (!mailType) {
                    alert('Please select a mail type.');
                    return;
                }
                if (!selectedCheck) {
                    alert('Please select a policy.');
                    return;
                }

                currentPolicyId = selectedCheck.dataset.policy_id;
                currentMailType = mailType;

                fetch("{{ route('get_mail_template') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            policy_id: currentPolicyId,
                            mail_type: currentMailType
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            if (mailPreviewEditor) {
                                mailPreviewEditor.setData(data.content);
                            }
                            const modal = new bootstrap.Modal(document.getElementById('MailPreviewModal'));
                            modal.show();
                        } else {
                            alert('Failed to load template.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error loading mail preview.');
                    });
            });

            document.getElementById('send_mail_btn').addEventListener('click', function() {

                if (!currentPolicyId || !currentMailType) {
                    alert('No policy selected.');
                    return;
                }

                const content = mailPreviewEditor ? mailPreviewEditor.getData() : '';

                if (!content.trim()) {
                    alert('Mail content cannot be empty.');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Sending...';

                fetch("{{ route('send_mail_log') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            policy_id: currentPolicyId,
                            mail_type: currentMailType,
                            content: content
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            bootstrap.Modal.getInstance(
                                document.getElementById('MailPreviewModal')
                            ).hide();

                            const toastEl = document.getElementById('clientSelectToast');
                            const toastBody = toastEl.querySelector('.toast-body');
                            toastBody.textContent = data.message;
                            new bootstrap.Toast(toastEl).show();
                        } else {
                            alert(data.message || 'Failed to send mail.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error sending mail.');
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.textContent = 'Send Mail';
                    });
            });

        });
    })();
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const successMsg = document.getElementById('successMessage');
        if (successMsg) {
            setTimeout(() => {
                successMsg.style.transition = "opacity 0.5s";
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 500);
            }, 5000);
        }
    });
</script>


   
<script src="{{asset('js/renewal_section.js')}}?v={{ time() }}"></script>
@endsection