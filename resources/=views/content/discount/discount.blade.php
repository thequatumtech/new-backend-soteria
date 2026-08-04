@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">
<link rel="stylesheet" href="{{asset('css/discount.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Discount Coupon Code</div>
            <div class="py-1">
                <button class="btn" data-bs-toggle="modal" data-bs-target="#cnccm">
                    <img src="{{asset ('img/icon-add.png')}}" alt="">
                </button>
            </div>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>

                        <th>No.</th>
                        <th>Date</th>
                        <th>Coupon Code</th>
                        <th>Insurance Name</th>
                        <th>Percentage</th>
                        <th>Active/Inactive</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discountdata as $key => $value)
                    <tr>
                        <td>{{$key + 1 }}</td>
                        <td>{{ $value->created_at->format('d-m-Y') }}</td>
                        <td>{{ $value->	coupan_code }}</td>
                        <td>{{ $value->insurance_type }}</td>
                        <td>{{ $value->discount_percentage }}</td>
                        <td>{{ $value->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
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
<div class="modal fade" id="cnccm" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-center modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header text-white border-0" style="background-color: #104E9E;">
                <h1 class="modal-title fs-5 fw-bold"> Create New Coupon Code </h1>
                <button class="btn" data-bs-dismiss="modal">
                    <img src="{{asset ('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <form action="{{route('discount.create')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row g-0 p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row g-0">
                                <div class="col-12">
                                    <div class="pb-2"> Coupon Code </div>
                                    <div class="shadow-1 p-2" style="padding-bottom: 2.5rem;">
                                        <input class="input-1" type="text" name="code" id="name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-12 pt-4 d-flex flex-column">
                                    <div class="pb-2"> Insurance Type </div>
                                    <select name="insurance_type" class="form-select select-1 rounded-0 flex-grow-1 border-0 shadow-1" style="height: 3.5rem;">
                                        <option value="s" selected> </option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-12 pt-4">
                                    <div class="pb-2"> Percentage </div>
                                    <div class=" shadow-1  p-2 bg-body ">
                                        <input name="discount_percentage" class="input-1" type="number" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-12  pt-4">
                                    <div class="pb-2"> Description </div>
                                    <div class="shadow-1 p-2 bg-body">
                                        <textarea class="input-1" name="discount_description" style="resize: none;min-height: 6.5rem;" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD </button>
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
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Delete Code</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('destoryCode')}}" method="post">

                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Data ?</h4>
                                    <input type="hidden" id="deleteing_id" name="delete_code_id">
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
        var code_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(code_id);
    });
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