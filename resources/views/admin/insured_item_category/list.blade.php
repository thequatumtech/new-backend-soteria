@extends('layouts.mainlayout')
@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold">{{__('messages.table_headers.insured_item_category')}}</div>
                <button data-bs-toggle="modal" data-bs-target="#addVehicleBrand" class="btn pe-0">
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
                    @foreach($categories as $key=> $single)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$single->name}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    <button class="btn p-0 m-0 editbtn" value="{{$single->id}}" data-bs-toggle="modal" data-bs-target="#">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </button>
                                    <button class="btn p-0 m-0 deletebtn" value="{{$single->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- START ADD Vehicle Brands -->
    <div class="modal fade " id="addVehicleBrand" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.add_insured_item_category')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('insured-items-categories.create')}}" id="add" method="post">
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
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">@php echo(__('messages.table_headers.add')); @endphp </button>
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
    <!-- END ADD Vehicle Brands -->
    <!-- START Edit & Update Vehicle Brands -->
    <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.edit_insured_item_category')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('insured-items-categories.update')}}" id="edit" method="post">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="insured_item_category_id" id="insured_item_category_id" value="">
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.table_headers.name')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="name" name="name" style="border: none;" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">@php echo(__('messages.table_headers.update')); @endphp </button>
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
    <!-- END Edit & Update Vehicle Brands -->
    <!-- START Delete Vehicle Brands -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.table_headers.delete_insured_item_category')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('insured-items-categories.destroy')}}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="delete_insured_item_category_id">
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
    <!-- END Delete Vehicle Brands -->


@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Close modal when clicking outside
            $('.modal').click(function(event) {
                if ($(event.target).hasClass('modal')) {
                    $(this).modal('hide');
                }
            });

            // Close modal when clicking on close button
            $('.modal .close').click(function() {
                $(this).closest('.modal').modal('hide');
            });

            // Reload modal content when modal is closed
            // $('.modal').on('hidden.bs.modal', function() {
            //     location.reload();
            // });

            var table = $('#example').DataTable({
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


        // delete
        $(document).on('click', '.deletebtn', function() {
            var insured_item_category_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(insured_item_category_id);
        });

        // edit
        $(document).ready(function() {
            $(document).on('click', '.editbtn', function() {
                var insured_item_category_id = $(this).val();

                $('#editModal').modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ route('insured-items-categories.edit', '') }}/" + insured_item_category_id,
                    dataType: "json",
                    success: function(data) {
                        var insured_item_category = data.categories;
                        $('#insured_item_category_id').val(insured_item_category.id);
                        $('#name').val(insured_item_category.name);
                    }
                });
                $("#edit").validate();
            });
        });
    </script>
@endsection

