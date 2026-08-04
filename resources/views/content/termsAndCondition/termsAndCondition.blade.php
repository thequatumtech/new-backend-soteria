@extends('layouts.mainlayout')
@section('style')
<style>
    input[type="file"] {
        /* height: 50px;
                cursor: pointer;
                margin-top: -40px;
                opacity: 0;
                position: relative; */
    }

    .file-margin {
        text-align: center;
    }

    .file-margin .error {
        text-align: center;
    }
</style>
@endsection
@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold"> {{ __('messages.terms_and_conditions.terms_and_condition') }}
            </div>
            {{-- @if (empty($termsAndCondition->id)) --}}
            <button data-bs-toggle="modal" data-bs-target="#addContactUs" class="btn pe-0">
                <img src="{{ asset('img/icon-add.png') }}" alt="">
            </button>
            {{-- @endif --}}
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('messages.terms_and_conditions.terms_and_condition') }}</th>
                        <th class="no-order" width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($termsAndCondition as $item)
                    <tr>
                        <td>
                            {!! $item->message !!}
                        </td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <a href="{{ asset('uploads/terms_and_conditions/' . $item->file) }}" target="_blank"
                                    class="btn p-0 m-0 btn-custom" @if(empty($item->file)) style="pointer-events: none; opacity: 0.5;" @endif>
                                    <img src="{{ asset('img/icon-eye.png') }}" alt="View" title="View">
                                </a>
                                @if (!empty($item->message))
                                <button type="button" class="btn p-0 m-0 editbtn"
                                    data-id="{{ $item->id }}"
                                    data-message="{{ htmlentities($item->message) }}"
                                    data-file="{{ $item->file }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <img src="{{ asset('img/icon-edit.png') }}" alt="Edit">
                                </button>

                                @endif
                                <button class="btn p-0 m-0 deletebtn" value="{{ $item->id }}">
                                    <img src="{{ asset('img/icon-delete.png') }}" alt="Delete">
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td>No Data Found</td>
                        <td></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade " id="addContactUs" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{ __('messages.terms_and_conditions.add_terms_and_condition') }} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{ asset('img/icon-close.svg') }}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <form id="termsForm" action="{{ route('terms-and-conditions.create') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div>
                                        {{ __('messages.terms_and_conditions.terms_and_condition') }}
                                    </div>
                                    <textarea name="message" class="ceditor" id="message"></textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="upload_file" class="form-label">Upload</label>
                                <input type="file" class="form-control" id="upload_file" name="upload_file">
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit"
                                            class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                            style="background-color: #EF7C00;">
                                            {{ __('messages.terms_and_conditions.save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{ __('messages.terms_and_conditions.add_terms_and_condition') }} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{ asset('img/icon-close.svg') }}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <form id="termsFormEdit" action="{{ route('terms-and-conditions.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div>
                                        {{ __('messages.terms_and_conditions.terms_and_condition') }}
                                    </div>
                                    <input type="hidden" name="id" id="edit_id" value="">
                                    <!-- Edit Modal -->
                                    <textarea name="message" class="ceditor" id="edit_message"></textarea>

                                </div>
                                <div class="mt-4">
                                    <label for="upload_file" class="form-label">Upload</label>
                                    <input type="file" class="form-control" id="upload_file" name="terms_file">
                                </div>
                                @php
                                $fileExtension = pathinfo($item->file ?? '', PATHINFO_EXTENSION);
                                @endphp

                                @if (!empty($item->file))
                                <p><a href="{{ asset('uploads/terms_and_conditions/' . $item->file) }}" download class="btn btn-link">
                                        Download File
                                    </a></p>
                                @else
                                <p>No file uploaded.</p>
                                @endif

                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit"
                                            class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                            style="background-color: #EF7C00;">
                                            {{ __('messages.terms_and_conditions.save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{ __('messages.terms_and_conditions.terms_and_condition') }} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{ asset('img/icon-close.svg') }}" alt="">
                </button>
            </div>

            <form action="{{ route('terms-and-conditions.destroy') }}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4> {{ __('messages.table_headers.confirm_to_delete_data') }} </h4>
                                    <input type="hidden" id="deleteing_id" name="delete_terms_id">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit"
                                        class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                        style="background-color: #EF7C00;">
                                        {{ __('messages.table_headers.yes_delete') }} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            info: false,
        });


        // delete
        $(document).on('click', '.deletebtn', function() {
            var terms_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(terms_id);
        });

        $('input[type="file"]').click(function(e) {
            e.stopImmediatePropagation();
        }).change(function(e) {
            var fileName = e.target.files[0].name;
            $(this).parents('.file-upload').find('.custom-file-label').html(fileName);
        });
        @if(!empty($termsAndCondition -> id))
        editValidate()
        @else
        addValidate();
        @endif
    });

    function addValidate() {
        $.validator.addMethod("requireOne", function(value, element) {
            var textareaValue = $('#message').val().trim();
            var fileValue = $('#terms_file').val();
            return textareaValue !== "" || (fileValue !== "" && fileValue != undefined);
        }, "Either the textarea or the file is required.");

        $("#termsForm").validate({
            onkeyup: false,
            ignore: [],
            rules: {
                message: {
                    requireOne: true
                },
                terms_file: {
                    requireOne: true,
                    accept: "jpg,jpeg,png,gif,bmp,pdf"
                },
            },
            messages: {
                message: {
                    requireOne: "Please enter your message or Upload file"
                },
                terms_file: {
                    requireOne: "Please enter your message or Upload file",
                    accept: "Please select a valid image or PDF file."
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }

    function editValidate() {
        $("#termsFormEdit").validate({
            onkeyup: false,
            ignore: [],
            rules: {
                message: {
                    required: true
                }
            },
            messages: {
                message: {
                    required: "Please enter your message"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.editbtn').forEach(btn => {
            btn.addEventListener('click', function() {

                const id = this.getAttribute('data-id');
                const message = decodeHTMLEntities(this.getAttribute('data-message'));
                const file = this.getAttribute('data-file');

                document.getElementById('edit_id').value = id;

                setTimeout(() => {
                    if (window.ckEditors && window.ckEditors['edit_message']) {
                        window.ckEditors['edit_message'].setData(message);
                    }
                }, 200);

                let fileBox = document.getElementById('edit_old_file');

                if (file && file !== "") {

                    let ext = file.split('.').pop().toLowerCase();

                    if (["jpg", "jpeg", "png", "gif", "bmp", "webp"].includes(ext)) {
                        fileBox.innerHTML = `
                        <p><strong>Old File:</strong></p>
                        <img src="/uploads/terms_and_conditions/${file}"
                             style="max-width:150px;border:1px solid #ddd;border-radius:5px;">
                    `;
                    } else {
                        fileBox.innerHTML = `
                        <p><strong>Old File:</strong>
                            <a href="/uploads/terms_and_conditions/${file}" target="_blank">
                                ${file}
                            </a>
                        </p>
                    `;
                    }

                } else {
                    fileBox.innerHTML = `<p><em>No old file uploaded.</em></p>`;
                }

            });
        });

    });

    function decodeHTMLEntities(text) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }
</script>


@endsection