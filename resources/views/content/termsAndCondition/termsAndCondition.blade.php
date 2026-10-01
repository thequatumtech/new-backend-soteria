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
                <button data-bs-toggle="modal" data-bs-target="#addContactUs" class="btn pe-0">
                    <img src="{{ asset('img/icon-add.png') }}" alt="">
                </button>
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ __('messages.terms_and_conditions.terms_and_condition') }}</th>
                            <th class="no-order" width="5%">{{ __('messages.terms_and_conditions.action') }}</th>
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
                                            <button type="button" class="btn p-0 m-0 editbtn" data-id="{{ $item->id }}"
                                                data-message="{{ htmlentities($item->message) }}" data-file="{{ $item->file }}"
                                                data-arabic-file="{{ $item->arabic_file }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                                <img src="{{ asset('img/icon-edit.png') }}" alt="Edit">
                                            </button>
                                        @endif

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
                                    <label for="upload_file" class="form-label">
                                        {{ __('messages.terms_and_conditions.upload') }}
                                        {{ __('messages.clients.english') }}
                                    </label>

                                    <input type="file" class="form-control" id="upload_file" name="terms_file" accept=".jpg,.jpeg,.png,.gif,.bmp,.pdf">
                                </div>

                                <div class="mt-4">
                                    <label for="upload_file_arabic" class="form-label">
                                        {{ __('messages.terms_and_conditions.upload') }}
                                        {{ __('messages.clients.arabic') }}
                                    </label>

                                    <input type="file" class="form-control" id="upload_file_arabic" name="terms_file_arabic" accept=".pdf">
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
                                        <label for="edit_upload_file" class="form-label">
                                            {{ __('messages.terms_and_conditions.upload') }}
                                            {{ __('messages.clients.english') }}
                                        </label>
                                        <input type="file" class="form-control" id="edit_upload_file" name="terms_file"
                                            accept=".jpg,.jpeg,.png,.gif,.bmp,.pdf">
                                    </div>

                                    <div id="edit_old_file" class="mt-2"></div>

                                    <div class="mt-4">
                                        <label for="edit_upload_file_arabic" class="form-label">
                                            {{ __('messages.terms_and_conditions.upload') }}
                                            {{ __('messages.clients.arabic') }}
                                        </label>
                                        <input type="file" class="form-control" id="edit_upload_file_arabic" name="terms_file_arabic" accept=".pdf">
                                    </div>

                                    <div id="edit_old_arabic_file" class="mt-2"></div>

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
            @if(!empty($termsAndCondition->id))
            editValidate()
            @else
            addValidate();
            @endif
        });

        function addValidate() {
                $("#termsForm").validate({
                    onkeyup: false,
                    ignore: [],
                    rules: {
                        message: {
                            required: function () {
                                return !$('#upload_file').val() &&
                                    !$('#upload_file_arabic').val();
                            }
                        },
                        terms_file: {
                            required: function () {
                                return !$('#message').val().trim() &&
                                    !$('#upload_file_arabic').val();
                            },
                            accept: "jpg,jpeg,png,gif,bmp,pdf"
                        },
                        terms_file_arabic: {
                            required: function () {
                                return !$('#message').val().trim() &&
                                    !$('#upload_file').val();
                            },
                            accept: "pdf"
                        }
                    },
                    messages: {
                        message: {
                            required: "Please enter a message or upload a file."
                        },
                        terms_file: {
                            required: "Please upload a file or enter a message.",
                            accept: "Please select a valid English file."
                        },
                        terms_file_arabic: {
                            required: "Please upload a file or enter a message.",
                            accept: "Please select an Arabic PDF."
                        }
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
            }
             function editValidate() {
                    $("#termsFormEdit").validate({
                        onkeyup: false,
                        ignore: [],
                        rules: {
                            terms_file: {
                                accept: "jpg,jpeg,png,gif,bmp,pdf"
                            },
                            terms_file_arabic: {
                                accept: "pdf"
                            }
                        },
                        messages: {
                            terms_file: {
                                accept: "Please select a valid English file."
                            },
                            terms_file_arabic: {
                                accept: "Please select an Arabic PDF."
                            }
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

             document.querySelectorAll('.editbtn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const message = decodeHTMLEntities(
                        this.getAttribute('data-message') || ''
                    );
                    const file = this.getAttribute('data-file');
                    const arabicFile = this.getAttribute('data-arabic-file');

                    document.getElementById('edit_id').value = id;

                    // Set editor content
                    setTimeout(() => {
                        if (window.ckEditors && window.ckEditors['edit_message']) {
                            window.ckEditors['edit_message'].setData(message);
                        }
                    }, 200);

                    // Display existing English file
                    const fileBox = document.getElementById('edit_old_file');

                    if (file) {
                        const ext = file.split('.').pop().toLowerCase();
                        const fileUrl =
                            `/uploads/terms_and_conditions/${encodeURIComponent(file)}`;

                        if (["jpg", "jpeg", "png", "gif", "bmp", "webp"].includes(ext)) {
                            fileBox.innerHTML = `
                        <p><strong>Old English File:</strong></p>
                        <img src="${fileUrl}"
                            style="max-width:150px;border:1px solid #ddd;border-radius:5px;">
                    `;
                        } else {
                            fileBox.innerHTML = `
                        <p><strong>Old English File:</strong>
                            <a href="${fileUrl}" target="_blank" rel="noopener">
                                ${file}
                            </a>
                        </p>
                    `;
                        }
                    } else {
                        fileBox.innerHTML = `<p><em>No English file uploaded.</em></p>`;
                    }

                    // Display existing Arabic PDF
                    const arabicFileBox =
                        document.getElementById('edit_old_arabic_file');

                    if (arabicFile) {
                        const arabicFileUrl =
                            `/uploads/terms_and_conditions/${encodeURIComponent(arabicFile)}`;

                        arabicFileBox.innerHTML = `
                    <p><strong>Old Arabic PDF:</strong>
                        <a href="${arabicFileUrl}" target="_blank" rel="noopener">
                            ${arabicFile}
                        </a>
                    </p>
                `;
                    } else {
                        arabicFileBox.innerHTML =
                            `<p><em>No Arabic PDF uploaded.</em></p>`;
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
