@extends('layouts.mainlayout')
@section('style')
    <style>
        /* input[type="file"] {
            height: 50px;
            cursor: pointer;
            margin-top: -40px;
            opacity: 0;
            position: relative;
        } */
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
                <div class="text-white group-title fw-bold"> {{__('messages.terms_and_conditions.terms_and_condition')}} </div>
                @if(empty($termsAndCondition->id))
                    <button data-bs-toggle="modal" data-bs-target="#addContactUs" class="btn pe-0">
                        <img src="{{asset('img/icon-add.png')}}" alt="">
                    </button>
                @endif
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
                    @if(!empty($termsAndCondition->id))
                        <tr>
                            <td>{{ __('messages.terms_and_conditions.app_terms_and_condition') }}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <a href="{{ route('terms_and_conditions') }}" target="_blank" class="btn p-0 m-0 btn-custom">
                                        <img src="{{ asset('img/icon-eye.png') }}" alt="View" title="View">
                                    </a>
                                    @if(!empty($termsAndCondition->message))
                                        <button class="btn p-0 m-0 editbtn" value="{{ $termsAndCondition->id }}"
                                                data-val="{{ $termsAndCondition->message }}"
                                                data-bs-toggle="modal" data-bs-target="#editModal">
                                            <img src="{{ asset('img/icon-edit.png') }}" alt="Edit">
                                        </button>
                                    @endif
                                    <button class="btn p-0 m-0 deletebtn" value="{{ $termsAndCondition->id }}">
                                        <img src="{{ asset('img/icon-delete.png') }}" alt="Delete">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>No Data Found</td>
                            <td></td>
                        </tr>
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade " id="addContactUs" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.terms_and_conditions.add_terms_and_condition')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="termsForm" action="{{route('terms-and-conditions.create')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div>
                                        {{__('messages.terms_and_conditions.terms_and_condition')}}
                                        @if($termsAndCondition->id > 0)
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#DeleteModal" class="btn pe-0 add-agent-btn" style="color:#0d6efd"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></button>
                                        @endif
                                    </div>
                                    <input type="hidden" name="id" value= "{{$termsAndCondition->id}}">
                                    <textarea name="message" id="message">{{$termsAndCondition->message}}</textarea>
                                </div>
                            </div>
                            @if(empty($termsAndCondition->id))
                            <div class="text-center pt-5">
                                <h3>OR</h3>
                            </div>
                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="terms_file" id="terms_file" accept="image/*,application/pdf">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="terms_file-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.terms_and_conditions.save')}}</button>
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
                    <h1 class="modal-title fs-5"> {{__('messages.terms_and_conditions.add_terms_and_condition')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="termsFormEdit" action="{{route('terms-and-conditions.create')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div>
                                        {{__('messages.terms_and_conditions.terms_and_condition')}}
                                    </div>
                                    <input type="hidden" name="id" value= "{{$termsAndCondition->id}}">
                                    <textarea name="message" id="message">{{$termsAndCondition->message}}</textarea>
                                </div>
                                  <div class="mt-4">
                                        <label for="upload_file" class="form-label">Upload</label>
                                        <input type="file" class="form-control" id="upload_file" name="upload_file">
                                    </div>
                                      @php
                                        $fileExtension = pathinfo($termsAndCondition->file, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset($termsAndCondition->file) }}" alt="Uploaded Image" class="img-fluid" style="max-height: 200px;">
                                    @elseif ($fileExtension === 'pdf')
                                        <a href="{{ route('terms_and_conditions') }}" target="_blank" class="text-decoration-none mt-3">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </a>
                                    @else
                                        <p><a href="{{ asset($termsAndCondition->file) }}" target="_blank">Download File</a></p>
                                    @endif
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.terms_and_conditions.save')}}</button>
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
                    <h1 class="modal-title fs-5"> {{__('messages.terms_and_conditions.terms_and_condition')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('terms-and-conditions.destroy')}}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="delete_terms_id">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.table_headers.yes_delete')}} </button>
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

            $('input[type="file"]').click(function (e) {
                e.stopImmediatePropagation();
            }).change(function (e) {
                var fileName = e.target.files[0].name;
                $(this).parents('.file-upload').find('.custom-file-label').html(fileName);
            });
            @if(!empty($termsAndCondition->id))
                editValidate()
            @else
                addValidate();
            @endif
        });
        function addValidate(){
            $.validator.addMethod("requireOne", function (value, element) {
                // Get the values of both fields
                var textareaValue = $('#message').val().trim();
                var fileValue = $('#terms_file').val();
                // Check if at least one of them is filled out
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
                        accept: "jpg,jpeg,png,gif,bmp,pdf" // Accept only image & pdf files
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
        function editValidate(){
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
@endsection

