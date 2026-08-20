@extends('layouts.mainlayout')
@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">{{__('messages.pets_breed.pet_breed')}}</div>
            <button data-bs-toggle="modal" data-bs-target="#addBreed" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th class="no-show">{{__('messages.pets_breed.pet_breed_id')}}</th>
                        <th width="20%">{{__('messages.pets_breed.pet_breed_type')}}</th>
                        <th width="70%">{{__('messages.pets_breed.pet_breed_name')}}</th>
                        <th class="no-order" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($breeds as $key => $breed)
                    @if(!$breed->trashed())
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucfirst($breed->type) }}</td>
                        <td>{{ $breed->breed }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <button class="btn p-0 m-0 editbtn"
                                    value="{{ $breed->id }}"
                                    data-type="{{ $breed->type }}"
                                    data-val="{{ $breed->breed }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
                                <button class="btn p-0 m-0 deletebtn" value="{{ $breed->id }}">
                                    <img src="{{asset('img/icon-delete.png')}}" alt="">
                                </button>
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

<!-- ADD Modal -->
<div class="modal fade" id="addBreed" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.pets_breed.pet_breed_add')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('pet_breed.create')}}" id="addForm" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>{{__('messages.pets_breed.pet_breed_type')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <select name="type" class="form-select" style="border: none;" required>
                                            <option value="dog">{{__('messages.pets_breed.pet_breed_dog')}}</option>
                                            <option value="cat">{{__('messages.pets_breed.pet_breed_cat')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>{{__('messages.pets_breed.pet_breed_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="breed" style="border: none;" class="form-control" required maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4" style="border: none;">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.pets_breed.pet_breed_add_button')}}</button>
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

<!-- EDIT Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.plans.pet_breed_update')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('pet_breed.update')}}" id="editForm" method="post">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>{{__('messages.pets_breed.pet_breed_type')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <select name="type" id="type_edit" class="form-select" style="border: none;" required>
                                            <option value="dog">{{__('messages.pets_breed.pet_breed_dog')}}</option>
                                            <option value="cat">{{__('messages.pets_breed.pet_breed_cat')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>{{__('messages.pets_breed.pet_breed_name')}}</div>
                                    <input type="hidden" id="breed_id" name="breed_id" value="">
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="breed_edit" name="breed" style="border: none;" class="form-control" required maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4" style="border: none;">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.pets_breed.pet_breed_update_button')}}</button>
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

<!-- DELETE Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5">{{__('messages.pets_breed.pet_breed_delete')}}</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('pet_breed.destroy')}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>{{__('messages.table_headers.confirm_to_delete_data')}}</h4>
                                    <input type="hidden" id="deleteing_id" name="breed_id">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4" style="border: none;">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.table_headers.yes_delete')}}</button>
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
            "order": [
                [1, 'asc']
            ],
            "columnDefs": [{
                    "targets": "no-order",
                    "orderable": false
                },
                {
                    "targets": "no-show",
                    visible: false,
                    searchable: false
                }
            ]
        });
        $("#addForm").validate();
    });

    $(document).on('click', '.editbtn', function() {
        var breed_id = $(this).val();
        var val = $(this).attr("data-val");
        var type = $(this).attr("data-type");

        $("#breed_edit").val(val);
        $("#breed_id").val(breed_id);
        $("#type_edit").val(type);

        $('#editModal').modal('show');
        $("#editForm").validate();
    });

    $(document).on('click', '.deletebtn', function() {
        var breed_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(breed_id);
    });
</script>
@endsection