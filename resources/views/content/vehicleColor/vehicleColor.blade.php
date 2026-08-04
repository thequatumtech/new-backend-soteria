@extends('layouts.mainlayout')

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.table_headers.vehicle_color')}} </div>
                <button data-bs-toggle="modal" data-bs-target="#addcsvcountry" class="btn pe-0">
                    <img width="40" src="{{asset('img/file-upload.png')}}" alt="">
                </button>
                <button data-bs-toggle="modal" data-bs-target="#addVehicleColor" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
            </div>
            <div class="d-flex p-3 py-2">
                <a href="{{route('color.sample.csv')}}" class="ms-auto">
                    {{__('messages.cities.download_csv')}}
                </a>
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>

                        <th class="no-show"> {{__('messages.table_headers.id')}} </th>
                        <th width="90%"> {{__('messages.table_headers.name')}} </th>
                        <th class="no-order" width="10%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vehicleColors as $key=> $vehicleColor)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$vehicleColor->name}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button class="btn p-0 m-0 editbtn" value="{{$vehicleColor->id}}" data-bs-toggle="modal" data-bs-target="#">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </button>
                                    <button class="btn p-0 m-0 deletebtn" value="{{$vehicleColor->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- START ADD Vehicle Color -->
    <div class="modal fade " id="addVehicleColor" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.add_vehicle_color')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('vehicle-color.create')}}" id="add" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.table_headers.name')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="name" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.table_headers.add')}} </button>
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
    <!-- END ADD Vehicle Color -->
    <!-- START Edit & Update Vehicle Color -->
    <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.edit_vehicle_color')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('vehicle-color.update')}}" id="edit" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="vehicle_color_id" id="vehicle_color_id" value="">
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.table_headers.name')}} </div>
                                        <input type="hidden" id="vehicle_color_id" name="id" style="border: none;" value="">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="nameOld" name="name" style="border: none;" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.table_headers.update')}} </button>
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
    <!-- END Edit & Update Vehicle Color -->
    <!-- START Delete Vehicle Color -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.delete_vehicle_color')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('vehicle-color.destroy')}}" method="post">

                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="delete_vehicle_color_id">
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
    <!-- END Delete Vehicle Color -->

    <!-- START ADD CSV -->
    <div class="modal fade " id="addcsvcountry" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.add_vehicle_color')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('color.csv')}}" id= "addcsv" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.table_headers.add_vehicle_color')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="file" name="csv_file" id="csv_file" style="border: none;" required>
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
            $("#add").validate(); //validate
        });
    </script>
    <script>
        // delete
        $(document).on('click', '.deletebtn', function() {
            var vehicle_color_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(vehicle_color_id);
        });

        // edit
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var vehicle_color_id = $(this).val();

                $('#editModal').modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ route('vehicle-color.edit', '') }}/" + vehicle_color_id,
                    dataType: "json",
                    success: function(data) {
                        console.log("data", data);
                        var vehicle_color = data.vehicleColor;
                        $('#vehicle_color_id').val(vehicle_color.id);
                        $('#nameOld').val(vehicle_color.name);
                    }
                });

                $("#edit").validate();
            });
        });
    </script>
@endsection

