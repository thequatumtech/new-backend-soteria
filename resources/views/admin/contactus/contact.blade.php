@extends('layouts.mainlayout')
@section('style')
@endsection
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
        overflow-x: hidden;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-all;
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
        white-space: nowrap !important;
    }

    #file-label {
        cursor: pointer;
        margin-right: 10px;
    }
</style>

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div id="success-message-del" class="alert alert-success d-none"></div>
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{ __('messages.contact_us.contact_us') }} </div>

                <button data-bs-toggle="modal" data-bs-target="#ContactUsChat" class="btn pe-0">
                    <img src="{{ asset('img/icon-add.png') }}" alt="">
                </button>

            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ __('messages.contact_us.id')}}</th>
                            <th>{{ __('messages.contact_us.contact_name')}} </th>
                            <th>{{ __('messages.contact_us.contact_email')}} </th>
                            <th class="no-order no-search"> {{ __('messages.contact_us.action') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contactus as $single)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $single->contact_name }}</td>
                                <td>{{ $single->email }}</td>
                                <td>
                                    <div class="d-flex justify-content-evenly align-items-center px-2">
                                      <button type="button" data-client_id="{{ $single->id }}"
                                            class="btn p-0 m-0 btn-custom editbtn">
                                            <img src="{{ asset('img/icon-edit.png') }}" alt="Edit" title="Edit">
                                        </button>
                                        <button type="button" data-client_id="{{ $single->id }}" class="btn p-0 m-0 btn-custom deletebtn">
                                            <img src="{{ asset('img/icon-delete.png') }}" alt="Delete" title="Delete">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="client_id">
    </main>


    <!-- START ADD Vehicle Type -->
    <div class="modal fade" id="ContactUsChat" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5" id="modal-title">{{__('messages.contact_us.add') }} {{ __('messages.contact_us.contact_us') }}</h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{ asset('img/icon-close.svg') }}" alt="">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                     <div class="alert alert-success mt-3 d-none" id="success-message"></div>
                        <form class="container" id="contact-form" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <input type="hidden" name="contact_id" id="contact_id">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.contact_us.contact_name')}}</label>
                                <input type="text" class="form-control" id="name" name="contact_name" placeholder="Enter your name">
                                <div class="invalid-feedback" id="contact_name-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('messages.contact_us.email_address')}}</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email">
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">{{ __('messages.contact_us.mobile')}}</label>
                                <input type="tel" class="form-control" id="mobile" name="mobile"
                                    placeholder="Enter your mobile number">
                                <div class="invalid-feedback" id="mobile-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('messages.contact_us.address')}}</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your address"></textarea>
                                <div class="invalid-feedback" id="address-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">{{ __('messages.contact_us.message')}}</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Type your message here"></textarea>
                                <div class="invalid-feedback" id="message-error"></div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #104E9E;">{{ __('messages.contact_us.submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END ADD Vehicle Type -->
    <!-- END ADD Vehicle Type -->


    {{-- </div> --}}

   <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Plan</h5>
                   <button data-bs-dismiss="modal" class="btn">
                        <img src="{{ asset('img/icon-close.svg') }}" alt="">
                    </button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4 fs-5">Confirm to Delete Data?</p>
                <button id="confirmDeleteBtn" class="btn btn-warning text-white px-4">YES DELETE</button>
            </div>
            </div>
        </div>
        </div>

@endsection

@section('script')
    <script>
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
    <script>
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            var formData = new FormData(this);

            ['email', 'mobile', 'address', 'message', 'contact_name'].forEach(function(field) {
                $('#' + field + '-error').text('');
                $('#' + field).removeClass('is-invalid');
            });

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#success-message').text(data.message || 'Contact us has been saved successfully!').removeClass('d-none');
                    form[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#' + field + '-error').text(messages[0]);
                            $('#' + field).addClass('is-invalid');
                        });
                    }
                }
            });
        });

        var contactEditRoute = "{{ route('contactus.edit', ['id' => '__id__']) }}";
        var contactUpdateRoute = "{{ route('contactus.update', ['id' => '__id__']) }}";

        function resetModalToAdd() {
            $('#modal-title').text("{{__('messages.contact_us.add') }} {{ __('messages.contact_us.contact_us') }}");
            $('#contact-form').attr('action', "{{ route('contact.store') }}");
            $('#form_method').val('POST');
            $('#submit-btn').text('Submit');
            $('#contact_id').val('');
            $('#contact-form')[0].reset();
            
            // Clear any previous errors
            ['email', 'mobile', 'address', 'message', 'contact_name'].forEach(function(field) {
                $('#' + field + '-error').text('');
                $('#' + field).removeClass('is-invalid');
            });
            $('#success-message').addClass('d-none');
        }

        $(document).on('click', '.editbtn', function () {
            var clientId = $(this).data('client_id');
            var fetchUrl = contactEditRoute.replace('__id__', clientId);
            var updateUrl = contactUpdateRoute.replace('__id__', clientId);
            
            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: function (data) {
                    $('#modal-title').text("{{__('messages.contact_us.edit') }} {{ __('messages.contact_us.contact_us') }}");
                    
                    $('#name').val(data.contact_name);
                    $('#email').val(data.email);
                    $('#mobile').val(data.mobile);
                    $('#address').val(data.address);
                    $('#message').val(data.message);
                    $('#contact_id').val(clientId);
                    
                    $('#contact-form').attr('action', updateUrl);
                    $('#form_method').val('PUT');
                    $('#submit-btn').text('Update');
                    
                    ['email', 'mobile', 'address', 'message', 'contact_name'].forEach(function(field) {
                        $('#' + field + '-error').text('');
                        $('#' + field).removeClass('is-invalid');
                    });
                    $('#success-message').addClass('d-none');
                    
                    $('#ContactUsChat').modal('show');
                },
                error: function () {
                    alert('Failed to fetch contact details.');
                }
            });
        });
        $('#ContactUsChat').on('hidden.bs.modal', function () {
            resetModalToAdd();
        });
        $(document).on('click', '.add-contact-btn', function() {
            resetModalToAdd();
            $('#ContactUsChat').modal('show');
        });
    </script>
    <script>
        let deleteId = null; // Store the contact id to delete

        // On delete button click
        $(document).on('click', '.deletebtn', function () {
            deleteId = $(this).data('client_id');
            $('#deleteConfirmModal').modal('show');
        });

        // On confirm delete
        $('#confirmDeleteBtn').click(function () {
            if (!deleteId) return;

            const deleteUrl = "{{ route('contactus.delete', '__id__') }}".replace('__id__', deleteId);

            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#deleteConfirmModal').modal('hide');

                    // Show success message
                    $('#success-message-del')
                        .removeClass('d-none')
                        .text(response.message);

                    setTimeout(() => {
                        $('#success-message-del').addClass('d-none').text('');
                    }, 3000);

                    // Optionally remove the row without reload
                    $('button[data-client_id="' + deleteId + '"]').closest('tr').remove();
                    deleteId = null;
                },
                error: function () {
                    alert('Delete failed.');
                    $('#deleteConfirmModal').modal('hide');
                }
            });
        });
    </script>


@endsection
