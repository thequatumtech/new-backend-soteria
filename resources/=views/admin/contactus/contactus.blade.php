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
        word-wrap: break-word; /* Ensures the word breaks when necessary */
        overflow-wrap: break-word; /* Works similarly for modern browsers */
        word-break: break-all; /* Forces the word to break if it's too long */
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
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.contact_us.contact_messages')}} </div>
{{--
                <button data-bs-toggle="modal" data-bs-target="#addContactUs" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
--}}
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>
                        <th> {{__('messages.contact_us.date')}} </th>
                        <th> {{__('messages.contact_us.client')}} </th>
                        <th class="no-order no-search"> {{__('messages.contact_us.view')}} </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contactus as $single)
                        <tr>
                            <td>{{$single->latest_message}}</td>
                            <td>{{$single->client->full_name}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button type="button" data-client_id="{{$single->client_id}}" class="btn p-0 m-0 btn-custom viewbtn">
                                        <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
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
    <div class="modal fade " id="ContactUsChat" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.contact_us.chat')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container" style="overflow: auto;">
                            <div class="chat-box">
                                <div class="chat-messages" id="chat-messages">
                                </div>
                                <div class="chat-input">
                                    <input type="text" id="chat-input" placeholder="Type your message">
                                    <input type="file" id="file-input" accept="image/png, image/gif, image/jpeg, application/pdf" style="display: none;">
                                    <label for="file-input" id="file-label">Attach File</label>
                                    <button id="send-button">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ADD Vehicle Type -->
{{--

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
--}}

@endsection

@section('script')
    <script>
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/contactus.js')}}"></script>
@endsection

