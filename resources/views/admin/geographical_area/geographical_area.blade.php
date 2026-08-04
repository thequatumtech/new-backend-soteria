@extends('layouts.mainlayout')
@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold"> {{__('messages.geographical_area.geographical_area')}} </div>
            <button data-bs-toggle="modal" data-bs-target="#add_geographical_area" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th class="no-show"> {{__('messages.table_headers.id')}} </th>
                        <th width="45%"> {{__('messages.geographical_area.geographical_area')}} </th>
                        <!-- <th width="45%"> {{__('Countries')}} </th> -->
                        <th class="no-order" width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($geographical_area as $key => $value)
                    @if(!$value->trashed())
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        {{-- <td>
                            @php
                            $areaCountryIds = $value->countries ?? [];
                            $areaCountryNames = $countries->whereIn('id', $areaCountryIds)->pluck('name')->toArray();
                            @endphp
                            {{ implode(', ', $areaCountryNames) ?: '—' }}
                        </td>--}}
                        <!-- data-countries="{{ json_encode($value->countries ?? []) }}" -->
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <button class="btn p-0 m-0 editbtn"
                                    value="{{ $value->id }}"
                                    data-val="{{ $value->name }}"
                                    
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
                                <button class="btn p-0 m-0 deletebtn" value="{{ $value->id }}">
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

<!-- START ADD Geographical Area -->
<div class="modal fade" id="add_geographical_area" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.geographical_area.add_geographical_area')}} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('geographical_area.create')}}" id="add" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> {{__('messages.geographical_area.add_geographical_area')}} </div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="name" style="border: none;" maxlength="50" required>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('Countries')}}</div>
                                    <select name="countries[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2-add" style="height: 3.5rem;" multiple>
                                        @foreach($countries as $single)
                                        <option value="{{$single->id}}" {{in_array($single->id, old('countries', [])) ? 'selected' : ''}}>
                                            {{$single->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4" style="border: none;">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                            style="background-color: #EF7C00;">
                                            {{__('messages.age.add')}}
                                        </button>
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
<!-- END ADD Geographical Area -->

<!-- START Edit Geographical Area -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.geographical_area.update_geographical_area')}} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('geographical_area.update')}}" id="edit_cd" method="post">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div>{{__('messages.geographical_area.update_geographical_area')}}</div>
                                    <input type="hidden" id="cd_id" name="cd_id" value="">
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="name_edit" name="name" style="border: none;" maxlength="50" required>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('Countries')}}</div>
                                    <select name="countries[]" id="edit_countries" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2-edit" style="height: 3.5rem;" multiple>
                                        @foreach($countries as $single)
                                        <option value="{{$single->id}}">{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4" style="border: none;">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                            style="background-color: #EF7C00;">
                                            {{__('messages.age.update')}}
                                        </button>
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
<!-- END Edit Geographical Area -->

<!-- START Delete Geographical Area -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> {{__('messages.geographical_area.delete_geographical_area')}} </h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('geographical_area.destroy')}}" method="post">
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
                                <div class="pt-4" style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit"
                                        class="btn rounded-1 w-100 text-white opacity-50 p-2"
                                        style="background-color: #EF7C00;">
                                        {{__('messages.table_headers.yes_delete')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Delete Geographical Area -->
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

        // $('.select2-add').select2({
        //     dropdownParent: $('#add_geographical_area'),
        //     placeholder: "Select countries",
        //     allowClear: true
        // });

        // $('.select2-edit').select2({
        //     dropdownParent: $('#editModal'),
        //     placeholder: "Select countries",
        //     allowClear: true
        // });

        $("#add").validate();
    });

    // Delete button
    $(document).on('click', '.deletebtn', function() {
        var id = $(this).val();
        $('#deleteing_id').val(id);
        $('#DeleteModal').modal('show');
    });

    $(document).on('click', '.editbtn', function() {
        var id = $(this).val();
        var name = $(this).data('val');
        // var countries = $(this).data('countries');

        $("#cd_id").val(id);
        $("#name_edit").val(name);

        // var $editSelect = $('#edit_countries');
        // $editSelect.val(null).trigger('change');
        // if (countries && countries.length) {
        //     $editSelect.val(countries.map(String)).trigger('change');
        // }

        $('#editModal').modal('show');
        $("#edit_cd").validate();
    });
</script>
@endsection