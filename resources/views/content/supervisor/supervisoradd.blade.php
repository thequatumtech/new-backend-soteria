@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">
<link rel="stylesheet" href="{{asset('css/supervisor.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Supervisor</div>
            <button data-bs-target="#addnewsupervisor" data-bs-toggle="modal" class="btn pe-0">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>

                        <th>ID</th>
                        <th>Joined Date</th>
                        <th>Full Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supervisordata as $key => $value)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$value->joining_date}}</td>
                        <td>{{$value->svname}}</td>
                        <td>{{$value->svmobile_no}}</td>
                        <td>{{$value->svemail}}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                <a href="" class="" style="color: #939EAA !important;">More</a>
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
<!-- Add New Supervisor Modal -->
<div class="modal fade rounded-0 rounded-top" role="dialog" id="addnewsupervisor" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl rounded-0 rounded-top " role="document">
        <div class="modal-content rounded-0 rounded-top">
            <div class="modal-header text-white rounded-0 rounded-top" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5 text-white"> Add New Supervisor</h1>
                <button class="btn p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('supervisor.create')}}" method="post">
                @csrf
                <div class="modal-body px-1 px-md-3">
                    <div class="container d-flex flex-column gap-4" style="color: #92959A;">
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Full Name</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="svname" id="name" style="border: none;">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Email ID </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="email" name="svemail" id="email">
                                </div>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Mobile No. </div>
                                <div class="d-flex shadow1 p-2">
                                    <span class="input-group-text bg-body rounded-0 opacity-25 border-0"> +91</span>
                                    <span class="vr"></span>
                                    <input type="number" name="svmobile_no" class="form-control shadow-0">
                                </div>
                            </div>
                            <div class="col-12 col-lg d-flex flex-column input-active">
                                <div> Gender </div>
                                <select name="sv_gender" id="" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option> -- Select --</option>
                                    <option value="Male"> Male </option>
                                    <option value="Female"> Female </option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div>Joining Date</div>
                                <div class="shadow1 p-2 d-flex align-items-center">
                                    <input type="date" name="joining_date" id="jtime">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> National ID/Passport No. </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="nationalandpassportno" id="pno" placeholder="National ID or Passport No." style="border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Agent Commission </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="agent_commission" id="cno" placeholder="Write in Number" style="border: none;">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Override Commission </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="override_commission" id="alife" placeholder="Write in Number" style="border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 ms-auto">
                            <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade modal-xl" id="notif" tabindex="-1" role="dialog" aria-labelledby="notif" aria-hidden="true">
        <div class="modal-dialog h-100 m-0 d-flex align-items-end justify-content-center mx-auto" role="document">
            <div class="modal-content mb-5">
                <div class="modal-body bg-primary">
                    <div class="d-flex justify-content-between px-2 px-md-5">
                        <div class="text-white fw-bold py-3" style="font-size: 1.375rem;">
                            New supervisor is successfully added to your dashboard.
                        </div>
                        <button type="button" class="btn text-white" data-bs-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--update Supervisor Modal -->
<div class="modal fade rounded-0 rounded-top" role="dialog" id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl rounded-0 rounded-top " role="document">
        <div class="modal-content rounded-0 rounded-top">
            <div class="modal-header text-white rounded-0 rounded-top" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5 text-white"> Update Supervisor</h1>
                <button class="btn p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('supervisor.update')}}" method="post">
                @csrf
                <input type="hidden" name="supervisor_id" id="supervisor_id" value="">
                <div class="modal-body px-1 px-md-3">
                    <div class="container d-flex flex-column gap-4" style="color: #92959A;">
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Full Name</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="svname" id="svname" style="border: none;">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Email ID </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="email" name="svemail" id="svemail">
                                </div>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Mobile No. </div>
                                <div class="d-flex shadow1 p-2">
                                    <span class="input-group-text bg-body rounded-0 opacity-25 border-0"> +91</span>
                                    <span class="vr"></span>
                                    <input type="number" id="svmobile_no" name="svmobile_no" class="form-control shadow-0">
                                </div>
                            </div>
                            <div class="col-12 col-lg d-flex flex-column input-active">
                                <div> Gender </div>
                                <select name="sv_gender" id="sv_gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option> -- Select --</option>
                                    <option value="Male"> Male </option>
                                    <option value="Female"> Female </option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div>Joining Date</div>
                                <div class="shadow1 p-2 d-flex align-items-center">
                                    <input type="date" name="joining_date" id="joining_date">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> National ID/Passport No. </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="nationalandpassportno" id="nationalandpassportno" placeholder="National ID or Passport No." style="border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="row g-0 gap-4">
                            <div class="col-12 col-lg input-active">
                                <div> Agent Commission </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="agent_commission" id="agent_commission" placeholder="Write in Number" style="border: none;">
                                </div>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Override Commission </div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="number" name="override_commission" id="override_commission" placeholder="Write in Number" style="border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 ms-auto">
                            <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">UPDATE</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade modal-xl" id="notif" tabindex="-1" role="dialog" aria-labelledby="notif" aria-hidden="true">
        <div class="modal-dialog h-100 m-0 d-flex align-items-end justify-content-center mx-auto" role="document">
            <div class="modal-content mb-5">
                <div class="modal-body bg-primary">
                    <div class="d-flex justify-content-between px-2 px-md-5">
                        <div class="text-white fw-bold py-3" style="font-size: 1.375rem;">
                            New supervisor is successfully added to your dashboard.
                        </div>
                        <button type="button" class="btn text-white" data-bs-dismiss="modal">Okay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DELETE  -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Delete Customer</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('destorysupervisor')}}" method="post">

                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Data ?</h4>
                                    <input type="hidden" name="delete_supervisor_id" id="deleteing_id" >
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
        var supervisor_id = $(this).val();
        alert(supervisor_id)
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(supervisor_id);
    });

    // edit
    $(document).ready(function() {
        $(document).on('click', '.editbtn', function() {
            var supervisor_id = $(this).val();
            alert(supervisor_id);
            $('#editModal').modal('show');
            // Rest of your code

            $.ajax({
                type: "GET",
                url: "{{ route('supervisor.edit', '') }}/" + supervisor_id,
                dataType: "json",
                success: function(data) {
                    console.log("cutomer data", data);
                    var supervisor = data.supervisor;
                    $('#supervisor_id').val(supervisor.id);
                    $('#svname').val(supervisor.svname);
                    $('#svemail').val(supervisor.svemail);
                    $('#svmobile_no').val(supervisor.svmobile_no);
                    $('#sv_gender').val(supervisor.sv_gender);
                    $('#joining_date').val(supervisor.joining_date);
                    $('#nationalandpassportno').val(supervisor.nationalandpassportno);
                    $('#agent_commission').val(supervisor.agent_commission);
                    $('#override_commission').val(supervisor.override_commission);



                }
            });
        });
    });
</script>
<script>
    const toastTrigger = document.getElementById('successToastBtn')
    const toastLiveExample = document.getElementById('successToast')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', () => {
            const toast = new bootstrap.Toast(toastLiveExample)

            toast.show()
        })
    }
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endsection