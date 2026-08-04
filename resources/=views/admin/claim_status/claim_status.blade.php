@extends('layouts.mainlayout')
@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.claim_status.claim_status')}} </div>
                <button data-bs-toggle="modal" data-bs-target="#addage" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>

                        <th class="no-show"> {{__('messages.table_headers.id')}} </th>
                        <th width="90%"> {{__('messages.claim_status.claim_status')}} </th>
                        <th class="no-order" width="10%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($claim_status as $key=> $value)
                    @if(!$value->trashed())
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$value->name}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button class="btn p-0 m-0 editbtn" value="{{$value->id}}" data-val="{{$value->name}}" data-bs-toggle="modal" data-bs-target="#editModal">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </button>
                                    <button class="btn p-0 m-0 deletebtn" value="{{$value->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<!-- START ADD Age -->
<div class="modal fade " id="addage" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.claim_status.add_claim_status')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('claim_status.create')}}" id= "add" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.claim_status.add_claim_status')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="name" style="border: none;" maxlength="100" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.age.add')}} </button>
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
    <!-- END ADD Age -->

    <!-- START Edit & Update Age -->
    <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.claim_status.update_claim_status')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('claim_status.update')}}" id="edit_cd" method="post">
                    @method('PATCH')
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div>{{__('messages.claim_status.update_claim_status')}}</div>
                                        <input type="hidden" id="cd_id" name="cd_id" style="border: none;" value="">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="name_edit" name="name" style="border: none;" maxlength="100" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.age.update')}} </button>
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
    <!-- END Edit & Update Age -->

    <!-- START Delete Vehicle Type -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.claim_status.delete_claim_status')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('claim_status.destroy')}}" method="post">

                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="cd_id">
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [[1, 'asc']], // Sort by the second column in ascending order
                "columnDefs": [
                    {
                        "targets": "no-order", // class name for the column where ordering is disabled
                        "orderable": false
                    },
                    {
                        "targets": "no-show",
                        visible: false,
                        searchable: false
                    },  //{ "orderable": true, "targets": 2 } // Disable sorting for the first column (zero-based index)

                ]
            });
            $("#add").validate();
        });
    </script>
    <script>
        // delete
        $(document).on('click', '.deletebtn', function() {
            var cd_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(cd_id);
        });
        $(document).on('click', '.editbtn', function() {
                var cd_id = $(this).val();
                var val = $(this).attr("data-val");
                // alert(cd_id+" =============== "+val);
                $("#name_edit").val(val);
                $("#cd_id").val(cd_id);
                $('#editModal').modal('show');
                $("#edit_cd").validate();

        });

        $(document).on('click', '.deletebtn', function() {
            var age_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(age_id);
        });
    </script>


@endsection

