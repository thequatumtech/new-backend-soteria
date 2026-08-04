@extends('layouts.mainlayout')
@section('content')
<style>
    select{
        border: none;
        font-family: 'Nunito';
        width: 100%;
        height: 3.5rem;
        font-weight: 600;
    }
</style>
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.table_headers.vehicle_type')}} </div>
                <button data-bs-toggle="modal" data-bs-target="#addVehicleType" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
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
                    @foreach($vehicleTypes as $key=> $vehicleType)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$vehicleType->name}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button class="btn p-0 m-0 editbtn" value="{{$vehicleType->id}}" data-bs-toggle="modal" data-bs-target="#">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </button>
                                    <button class="btn p-0 m-0 deletebtn" value="{{$vehicleType->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
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
    <div class="modal fade " id="addVehicleType" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.add_vehicle_type')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('vehicle-type.create')}}" method="post" id="add" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                     <div class="col-12 col-lg-6 pt-2">
                                        <div> {{ __('messages.sidebar_titles.vehicle_brand') }} </div>
                                        <div class="" style="padding-bottom: 2.5rem;">
                                            <select name="vehicle_brand_id" style="border: none; width: 100%;" class="form-select rounded-0 flex-grow-1 border-0 shadow1" required>
                                                <option value="">{{ __('messages.clients.select') }}</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>                             
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
    <!-- END ADD Vehicle Type -->
    <!-- START Edit & Update Vehicle Type -->
    <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.edit_vehicle_type')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('vehicle-type.update')}}" method="post" id="edit" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="vehicle_type_id" id="vehicle_type_id" value="">
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{ __('messages.sidebar_titles.vehicle_brand') }} </div>
                                        <div class="" style="padding-bottom: 2.5rem;">
                                           <select name="vehicle_brand_id" id="vehicle_brand_id" style="border: none; width: 100%;" class="form-select rounded-0 flex-grow-1 border-0 shadow1" required>
                                                <option value="">{{ __('messages.clients.select') }}</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>    
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div>{{__('messages.table_headers.name')}}</div>
                                        <input type="hidden" id="vehicle_type_id" name="id" style="border: none;" value="">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="name" name="name" style="border: none;" value="">
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
    <!-- END Edit & Update Vehicle Type -->
    <!-- START Delete Vehicle Type -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.delete_vehicle_type')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('vehicle-type.destroy')}}" method="post">

                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="delete_vehicle_type_id">
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
                "order": [[1, 'asc']], 
                "columnDefs": [{
                    "targets": "no-order", 
                    "orderable": false
                    },
                    {
                        "targets": "no-show",
                        visible: false,
                        searchable: false
                    }, 
                ]
            });
            $("#add").validate();
        });
    </script>
    <script>
        // delete
        $(document).on('click', '.deletebtn', function() {
            var vehicle_type_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(vehicle_type_id);
        });

        // edit
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var vehicle_type_id = $(this).val();

                $('#editModal').modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ route('vehicle-type.edit', '') }}/" + vehicle_type_id,
                    dataType: "json",
                    success: function(data) {
                        console.log("data", data);
                        var vehicle_type = data.vehicleType;
                        $('#vehicle_type_id').val(vehicle_type.id);
                        $('#name').val(vehicle_type.name);
                        $('#vehicle_brand_id').val(vehicle_type.vehicle_brand_id);
                    }
                });
                $("#edit").validate();
            });
        });
    </script>
@endsection

