@extends('layouts.mainlayout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/agent.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
    </style>
@endsection

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.contact_us.contact_us')}} </div>
                <button data-bs-toggle="modal" data-bs-target="#addContactUs" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>

                        <th> {{__('messages.contact_us.id')}} </th>
                        <th> {{__('messages.contact_us.mobile')}} </th>
                        <th> {{__('messages.contact_us.message')}} </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contactUs as $key=> $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$value->mobile}}</td>
                            <td>{{$value->message}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button class="btn p-0 m-0 deletebtn" value="{{$value->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- START ADD Vehicle Type -->
    <div class="modal fade " id="addContactUs" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.contact_us.add_contact_us')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form id="contactUsForm" action="{{route('contact-us.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.contact_us.mobile')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="number" name="mobile" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div> {{__('messages.contact_us.message')}} </div>
                                        <div id="editor" name="message"></div>
                                        <input type="hidden" id="editor-content" name="message" />
                                    </div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.contact_us.add')}} </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- END ADD Vehicle Type -->
    
    <!-- START Delete Vehicle Type -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.contact_us.delete_contact_us')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('contact-us.destroy')}}" method="post">

                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="delete_contact_us_id">
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
    <!-- END Delete Vehicle Type -->

@endsection

@section('script')

<<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            // CKEDITOR.replace('editor');
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(editor => {
                    // Get the CKEditor instance
                    editor.model.document.on('change:data', () => {
                        // Update the value of the hidden input field with CKEditor content
                        document.querySelector('#editor-content').value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
                
            $('#contactUsForm').validate({
                rules: {
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    message: {
                        required: true
                    }
                },
                messages: {
                    mobile: {
                        required: "Please enter your mobile number",
                        digits: "Please enter a valid mobile number",
                        minlength: "Mobile number must be 10 digits",
                        maxlength: "Mobile number must be 10 digits"
                    },
                    message: {
                        required: "Please enter your message"
                    }
                }
            });
        });
        
        // delete
        $(document).on('click', '.deletebtn', function() {
            var contact_us_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(contact_us_id);
        });

    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

@endsection

