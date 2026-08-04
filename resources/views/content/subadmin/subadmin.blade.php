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
            <div class="text-white group-title fw-bold">Add New Sub-admin</div>
            <button data-bs-toggle="modal" data-bs-target="#addCustomer" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
            <!-- <div class="py-1">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </div> -->
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile No.</th>
                        <th>occupation</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subadmindetails as $key => $value)
                    <tr>
                        <td>{{$key + 1 }}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->email}}</td>
                        <td>{{$value->mobile_no}}</td>
                        <td>{{$value->national_id}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <button class="btn p-0 m-0 editbtn" value="{{$value->id}}" data-bs-toggle="modal" data-bs-target="#">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
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
<!-- Add Subadmin -->
<div class="modal fade " id="addCustomer" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Add Subadmin</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('subadmin.create')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="full_name" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="text" class="form-control" name="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> ID</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="admin_id" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Password</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="password" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> National ID </div>

                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="national_id" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD </button>
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
<!-- Update Subadmin -->
<div class="modal fade " id="updateCustomer" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Update Subadmin</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('subadminUpdate.update')}}" method="post">
                @csrf
                <input type="hidden" name="subadmin_id" id="subadmin_id" value="">
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="full_name" id="full_name" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="text" class="form-control" name="mobile_no" id="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> ID</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="admin_id" id="admin_id" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Password</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="password" id="password" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> National ID </div>

                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="national_id" id="national_id" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">UPDATE </button>
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
<!-- Delete Subadmin -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Delete Subadmin</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('destorySubadmin')}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Data ?</h4>
                                    <input type="hidden" id="deleteing_id" name="delete_subadmin_id">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">YES DELETE</button>
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
    // delete
    $(document).on('click', '.deletebtn', function() {
        var subadmin_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(subadmin_id);
    });
    // upadte
    $(document).ready(function() {
        $(document).on('click', '.editbtn', function() {
            var subadmin_id = $(this).val();

            $('#updateCustomer').modal('show');
            // Rest of your code

            $.ajax({
                type: "GET",
                url: "{{ route('subadmin.edit', '') }}/" + subadmin_id,
                dataType: "json",
                success: function(data) {
                    console.log("Subadmin", data);
                    var subadmin = data.subadmin;
                    $('#subadmin_id').val(subadmin.id);
                    $('#full_name').val(subadmin.name);
                    $('#mobile_no').val(subadmin.mobile_no);
                    $('#admin_id').val(subadmin.email);
                    $('#password').val(subadmin.password);
                    $('#national_id').val(subadmin.national_id);
                    $('#gender').val(subadmin.gender);

                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script/left-nav.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endsection