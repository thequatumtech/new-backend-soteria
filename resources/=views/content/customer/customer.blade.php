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
            <div class="text-white group-title fw-bold">Customers</div>
            <button data-bs-toggle="modal" data-bs-target="#addCustomer" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
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
                    @foreach($customerdata as $key=> $customersdata)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{$customersdata->full_name}}</td>
                        <td>{{$customersdata->email}}</td>
                        <td>{{$customersdata->mobile_no}}</td>
                        <td>{{$customersdata->occupation}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <a href="" class="" style="color: #939EAA !important;">More</a>
                                <button class="btn p-0 m-0 editbtn" value="{{$customersdata->id}}" data-bs-toggle="modal" data-bs-target="#">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
                                <button class="btn p-0 m-0 deletebtn" value="{{$customersdata->id}}"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
<!-- START ADD Customers -->
<div class="modal fade " id="addCustomer" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Add Customer</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('customer.create')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="full_name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Email ID </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" name="email" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Occupation </div>
                                    <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" name="occupation" style="height: 3.5rem;" required>
                                        <option value="s" selected>--Select--</option>
                                        <option value="IT Engineer">IT Engineer</option>
                                        <option value="Mechanical Engineer">Mechanical Engineer</option>
                                        <option value="Civil Engineer">Civil Engineer</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="number" class="form-control" name="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Date of Birth</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="date" name="dob" id="jtime" style="border: none; font-weight: 330;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> House No. / Building Name </div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="housenoandbuildingname" id="jtime" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Street </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="street" class="form-control" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Country </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="country" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="india">India</option>
                                        <option value="usa">USA</option>
                                        <option value="uk">UK</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> City </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="city" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="bhavnagar">bhavnagar</option>
                                        <option value="rajkot">rajkot</option>
                                        <option value="baroda">baroda</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> District </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="district" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="dist1">dist1</option>
                                        <option value="dist2">dist2</option>
                                        <option value="dist3">dist3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12  pt-4">
                                    <div> Stamp of Insurance Comapany </div>
                                    <div class="card shadow1 border-0 rounded-0 file-upload " style="cursor: pointer;" onclick="fu()">
                                        <div class="card-body mx-auto">
                                            <div class="d-block mx-auto pt-3"> <img src="{{asset('img/icon-upload.png')}}"> </div>
                                        </div>
                                        <div class="card-body mx-auto ">
                                            <input type="file" name="stamp_of_company" class="d-none" id="1" onchange="labelc()" required>
                                            <div class="mx-auto" style="color: #ced4da;"> <label id="l1" class="custom-file-label"> Upload your File Here </label> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD </button>
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
<!-- END ADD Customers -->
<!-- START Edit & Update Customers -->
<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Edit Customer</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('customer.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id" value="">
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="full_name" name="full_name" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Email ID </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" id="email" name="email" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Occupation </div>
                                    <select class="form-select rounded-0 flex-grow-1 border-0 shadow1" id="occupation" name="occupation" style="height: 3.5rem;">
                                        <option value="s" selected>--Select--</option>
                                        <option value="IT Engineer">IT Engineer</option>
                                        <option value="Mechanical Engineer">Mechanical Engineer</option>
                                        <option value="Civil Engineer">Civil Engineer</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Mobile No. </div>
                                    <div class="input-group shadow1 p-2">
                                        <span class="input-group-text bg-body rounded-0 opacity-25" style="border-top:white; border-left: white; border-bottom:white ;"> + 91</span>
                                        <input type="number" id="mobile_no" class="form-control" name="mobile_no" style="border-top:white; border-right: white; border-bottom:white; outline: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div> Gender </div>
                                    <select name="gender" id="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="s" selected> --Select-- </option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Date of Birth</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="date" name="dob" id="dob" style="border: none; font-weight: 330;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> House No. / Building Name </div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="housenoandbuildingname" id="housenoandbuildingname" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div> Street </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="street" id="street" class="form-control" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Country </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="country" name="country" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="india">India</option>
                                        <option value="usa">USA</option>
                                        <option value="uk">UK</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> City </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="city" name="city" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="bhavnagar">bhavnagar</option>
                                        <option value="rajkot">rajkot</option>
                                        <option value="baroda">baroda</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> District </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="district" name="district" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="dist1">dist1</option>
                                        <option value="dist2">dist2</option>
                                        <option value="dist3">dist3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12  pt-4">
                                    <div> Stamp of Insurance Comapany </div>
                                    <div class="card shadow1 border-0 rounded-0 file-upload " style="cursor: pointer;" onclick="fu2()">
                                        <div class="card-body mx-auto">
                                            <div class="d-block mx-auto pt-3"> <img src="{{asset('img/icon-upload.png')}}"> </div>
                                        </div>
                                        <div class="card-body mx-auto ">
                                            <input type="file" name="stamp_of_company" class="d-none" id="2" onchange="labelc2()">
                                            <div class="mx-auto" style="color: #ced4da;"> <label id="l2" class="custom-file-label"> Upload your Excel Sheet Here </label> </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <table class="table table-striped" id="customer_edit_file">
                                            <thead>
                                                <tr>
                                                    <th>Index</th>
                                                    <th>File</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>

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
<!-- END Edit & Update Customers -->
<!-- START Delete Customers -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Delete Customer</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('destoryCustomer')}}" method="post">

                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Data ?</h4>
                                    <input type="hidden" id="deleteing_id" name="delete_customer_id">
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

<!-- END ADD Customers -->

@endsection

@section('script')
<script>
    let t1 = document.getElementById("l1");
    let f1 = document.getElementById("1");
    let t2 = document.getElementById("l2");
    let f2 = document.getElementById("2");

    function fu() {
        f1.click();
    }

    function fu2() {
        f2.click();
    }

    function labelc() {
        t1.innerText = f1.files[0].name;
    }

    function labelc2() {
        t2.innerText = f2.files[0].name;
    }
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script>
    // delete
    $(document).on('click', '.deletebtn', function() {
        var customer_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(customer_id);
    });

    // edit
    $(document).ready(function() {
        $(document).on('click', '.editbtn', function() {
            var customer_id = $(this).val();

            $('#editModal').modal('show');
            // Rest of your code

            $.ajax({
                type: "GET",
                url: "{{ route('customer.edit', '') }}/" + customer_id,
                dataType: "json",
                success: function(data) {
                    console.log("cutomer data", data);
                    var customer = data.customer;
                    $('#customer_id').val(customer.id);
                    $('#full_name').val(customer.full_name);
                    $('#email').val(customer.email);
                    $('#occupation').val(customer.occupation);
                    $('#mobile_no').val(customer.mobile_no);
                    $('#gender').val(customer.gender);
                    $('#dob').val(customer.dob);
                    $('#housenoandbuildingname').val(customer.housenoandbuildingname);
                    $('#street').val(customer.street);
                    $('#country').val(customer.country);
                    $('#city').val(customer.city);
                    $('#district').val(customer.district);
                    let tbody = $('#customer_edit_file tbody');
                    let html = '';
                    if (customer.customer_file) {
                        const {
                            name,
                            delete_url,
                            src,
                            download_url
                        } = customer.customer_file;
                        html += `<tr><td>1</td><td>${name}</td><td><a href="${delete_url}">Delete</a>&nbsp;<a href="${src}" target="_blank">View</a>&nbsp;<a href="${download_url}">Download</a></td></tr>`
                    }
                    tbody.html(html);
                    // $('#stamp_of_company').val(customer.stamp_of_company);

                    var occupationDropdown = $('#occupation');
                    occupationDropdown.empty();
                    for (var i = 1; i <= 3; i++) {
                        var option = $('<option>', {
                            value: i,
                            text: i
                        });

                        occupationDropdown.append(option);
                    }

                    // Set the selected option based on customer data
                    occupationDropdown.val(customer.occupation);
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endsection

