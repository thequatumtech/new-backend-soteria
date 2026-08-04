@extends('layouts.mainlayout')
@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.insured_item_sub_categories.insured_item_sub_categories')}} </div>
                <button data-bs-toggle="modal" data-bs-target="#addage" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </button>
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>

                        <th class="no-show"> {{__('messages.table_headers.id')}} </th>
                        <th width="45%"> {{__('messages.insured_item_sub_categories.category')}} </th>
                        <th width="45%"> {{__('messages.insured_item_sub_categories.sub_category')}} </th>
                        <th class="no-order" width="10%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_sub_categories as $key=> $single)
                        @if(!$single->trashed())
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{isset($single->category->name)&&!empty($single->category->name)?$single->category->name:'-'}}</td>
                                <td>{{$single->name}}</td>
                                <td>
                                    <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                        <button class="btn p-0 m-0 editbtn" value="{{$single->id}}" data-name="{{$single->name}}" data-brand="{{$single->insured_item_category_id}}" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <img src="{{asset('img/icon-edit.png')}}" alt="">
                                        </button>
                                        <button class="btn p-0 m-0 deletebtn" value="{{$single->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
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
                    <h1 class="modal-title fs-5"> {{__('messages.insured_item_sub_categories.add')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form action="{{route('insured_item_sub_categories.create')}}" id= "add" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.insured_item_sub_categories.category')}} </div>
                                        <select name="insured_item_category_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled hidden>--Select--</option>
                                            @foreach($all_categories as $single)
                                                <option value="{{$single->id}}">{{$single->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.insured_item_sub_categories.sub_category')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" required name="name" style="border: none;" maxlength="50">
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
                    <h1 class="modal-title fs-5"> {{__('messages.insured_item_sub_categories.edit')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('insured_item_sub_categories.update')}}" id="edit_age" method="post">
                    @csrf
                    <input type="hidden" id="insured_item_sub_category_id" name="insured_item_sub_category_id" style="border: none;" value="">
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div> {{__('messages.insured_item_sub_categories.category')}} </div>
                                        <select name="insured_item_category_id" id="edit_insured_item_category_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled hidden>--Select--</option>
                                            @foreach($all_categories as $single)
                                                <option value="{{$single->id}}">{{$single->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-6 pt-2">
                                        <div>{{__('messages.insured_item_sub_categories.sub_category')}}</div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" required name="name" id="name_edit" style="border: none;" maxlength="50">
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
                    <h1 class="modal-title fs-5"> {{__('messages.insured_item_sub_categories.delete')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('insured_item_sub_categories.delete')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="insured_item_sub_category_id">
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
            var age_id = $(this).val();
            $('#DeleteModal').modal('show');
            $('#deleteing_id').val(age_id);
        });
        $(document).on('click', '.editbtn', function() {
            var insured_item_sub_category_id = $(this).val();
            var name = $(this).attr("data-name");
            var insured_item_category_id = $(this).attr("data-brand");
            let edit_insured_item_category_id = '#edit_insured_item_category_id option[value="'+insured_item_category_id+'"]';
            $(edit_insured_item_category_id).attr("selected", "selected");

            $("#insured_item_sub_category_id").val(insured_item_sub_category_id);
            $("#name_edit").val(name);
            $('#editModal').modal('show');
            $("#edit_age").validate();
        });
    </script>


@endsection

