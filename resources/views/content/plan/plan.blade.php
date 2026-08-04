@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">
<link rel="stylesheet" href="{{asset('css/plan.css')}}">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    tr {
        cursor: pointer;
    }
</style>
@endsection


@section('content')
<main class="flex-grow-1 pt-3">
    <div class="container-fluid" style="box-shadow: 1px 7px 21px -9px rgba(0, 0, 0, 0.25); border-radius: 10px 10px 0px 0px;">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Active Plans</div>
            <button class="btn m-0 py-1" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body bg-white py-4 px-4 d-flex flex-column gap-3">
            <table id="example" class="table table-striped " style="width:100%">
                <thead>
                    <tr>

                        <th>No.</th>
                        <th>Added Date</th>
                        <th>Plan Name</th>
                        <th>Provided By</th>
                        <th>Duration</th>
                        <th>Gross Premium</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plandata as $key=>$value)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td> {{ $value->created_at->format('d/m/Y') }}</td>
                        <td>{{ $value->plan_name }}</td>
                        <td>{{ $value->company_name }}</td>
                        <td>{{ $value->limit }}</td>
                        <td>{{ $value->gross_premium }}</td>
                        <td>
                            <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">

                                <button class="btn p-0 m-0 editbtn" value="{{$value->id}}" data-bs-toggle="modal" data-bs-target="#">
                                    <img src="{{asset('img/icon-edit.png')}}" alt="">
                                </button>
                                <button class="btn p-0 m-0 deletebtn" value="">
                                    <img src="{{asset('img/icon-delete.png')}}" alt="">
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
<!-- create plan modal -->
<div class="modal fade rounded-0 rounded-top" role="dialog" id="addPlanModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl rounded-0 rounded-top modal-dialog-scrollable" role="document">
        <div class="modal-content rounded-0 rounded-top">
            <div class="modal-header text-white rounded-0 rounded-top px-4" style="background-color: #104E9E;">
                <div class="modal-title text-white fw-bold" style="font-size: 1.875rem;"> Add Insurance Plan</div>
                <button class="btn p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body px-1 px-md-3">
                <form action="{{route('plan.create')}}" method="post">
                    @csrf
                    <div class="container d-flex flex-column gap-4" style="color: #92959A;">
                        <div class="row g-0 gap-3">
                            <div class="col-12 col-lg input-active">
                                <div> Company Name</div>
                                <select name="company_name" id="company_name" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option selected> -- Select --</option>
                                    <option value="Aditya Birla">Aditya Birla </option>
                                    <option value="LIC"> LIC </option>
                                </select>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Line of Business</div>
                                <select name="lineofbussines" id="lineofbussines" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option selected> -- Select --</option>
                                    <option value="Line A">Line A </option>
                                    <option value="Line B"> Line B </option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-0 gap-3">
                            <div class="col-12 col-lg input-active">
                                <div> Plan Name </div>
                                <div class="d-flex shadow1 p-2">
                                    <input type="text" name="plan_name" class="form-control shadow-0" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button text-white" style="background: linear-gradient(153deg, #2A8CC1 0%, #2255A4 100%);" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Add Covers
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row g-0 gap-2">
                                                <div class="col table-responsive rounded border-secondary">
                                                    <table class="table table-bordered border border-2 border-secondary mb-0">
                                                        <thead>
                                                            <tr style="background-color: #6D7986;">
                                                                <th scope="col"></th>
                                                                <th scope="col">Name of Cover</th>
                                                                <th scope="col">Limit</th>
                                                                <th scope="col">Premium</th>
                                                                <th scope="col">Rate</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <div>
                                                                        <input type="checkbox" class="">
                                                                    </div>
                                                                </th>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <button class="btn">
                                                                            <img src="" alt="">
                                                                        </button>
                                                                        <button class="btn">
                                                                            <img src="" alt="">
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-auto d-flex align-items-end">
                                                    <button class="btn p-3 px-4" style="background-color: #2A8CC2;">
                                                        <img src="" alt="">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 text-dark fw-bold d-flex align-items-center justify-content-between" style="font-size: 1.375rem;">
                            <div>Schedule</div>
                            <div class="d-flex gap-2">
                                <button id="gb" data-bs-target="#schedule" data-bs-toggle="modal" class="btn p-0 m-0">
                                    <img src="" alt="">
                                </button>
                                <button class="btn p-0 m-0">
                                    <img src="" alt="">
                                </button>
                            </div>
                        </div>
                        <div class="inner-text-light">
                            <div class="col table-responsive rounded border-secondary">
                                <table class="table table-bordered border border-2 border-secondary mb-0">
                                    <thead>
                                        <tr style="background-color: #6D7986;">
                                            <th scope="col">Name of Plan</th>
                                            <th scope="col">Limit</th>
                                            <th scope="col">Premium</th>
                                            <th scope="col">Stamp</th>
                                            <th scope="col">Fees</th>
                                            <th scope="col">Sales Tax</th>
                                            <th scope="col">Net Premium</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Limit</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="limit" id="limit" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Fees</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="plan_fee" id="plan_fee" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Sales Tax</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="sales_tax" id="sales_tax" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Net Premium</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="net_premium" id="net_premium" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Gross Premium</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="gross_premium" id="gross_premium" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Commission</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="commission" id="commission" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Stamp Fee(%)</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="stamp_fee" id="stamp_fee" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Commission(%)</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="commission_percent" id="commission_percent" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 ms-auto">
                            <button data-bs-toggle="" data-bs-target="#notif" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end Create plan Model -->
<!-- update plan modal -->
<div class="modal fade rounded-0 rounded-top" role="dialog" id="updatemodel" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl rounded-0 rounded-top modal-dialog-scrollable" role="document">
        <div class="modal-content rounded-0 rounded-top">
            <div class="modal-header text-white rounded-0 rounded-top px-4" style="background-color: #104E9E;">
                <div class="modal-title text-white fw-bold" style="font-size: 1.875rem;"> Update Insurance Plan</div>
                <button class="btn p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body px-1 px-md-3">
                <form action="{{route('plan.update')}}" method="post">
                    @csrf
                    <input type="hidden" name="plan_id" id="plan_id" value="">
                    <div class="container d-flex flex-column gap-4" style="color: #92959A;">
                        <div class="row g-0 gap-3">
                            <div class="col-12 col-lg input-active">
                                <div> Company Name</div>
                                <select name="company_name" id="company_namee" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option selected> -- Select --</option>
                                    <option value="Aditya Birla">Aditya Birla </option>
                                    <option value="LIC"> LIC </option>
                                </select>
                            </div>
                            <div class="col-12 col-lg input-active">
                                <div> Line of Business</div>
                                <select name="lineofbussines" id="lineofbussiness" class="form-select rounded-0 flex-grow-1 border-0 shadow1">
                                    <option selected> -- Select --</option>
                                    <option value="Line A">Line A </option>
                                    <option value="Line B"> Line B </option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-0 gap-3">
                            <div class="col-12 col-lg input-active">
                                <div> Plan Name </div>
                                <div class="d-flex shadow1 p-2">
                                    <input type="text" id="plan_name" name="plan_name" class="form-control shadow-0" required>
                                </div>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button text-white" style="background: linear-gradient(153deg, #2A8CC1 0%, #2255A4 100%);" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Add Covers
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row g-0 gap-2">
                                                <div class="col table-responsive rounded border-secondary">
                                                    <table class="table table-bordered border border-2 border-secondary mb-0">
                                                        <thead>
                                                            <tr style="background-color: #6D7986;">
                                                                <th scope="col"></th>
                                                                <th scope="col">Name of Cover</th>
                                                                <th scope="col">Limit</th>
                                                                <th scope="col">Premium</th>
                                                                <th scope="col">Rate</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">
                                                                    <div>
                                                                        <input type="checkbox" class="">
                                                                    </div>
                                                                </th>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>---</td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <button class="btn">
                                                                            <img src="" alt="">
                                                                        </button>
                                                                        <button class="btn">
                                                                            <img src="" alt="">
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-auto d-flex align-items-end">
                                                    <button class="btn p-3 px-4" style="background-color: #2A8CC2;">
                                                        <img src="" alt="">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 text-dark fw-bold d-flex align-items-center justify-content-between" style="font-size: 1.375rem;">
                            <div>Schedule</div>
                            <div class="d-flex gap-2">
                                <button id="gb" data-bs-target="#schedule" data-bs-toggle="modal" class="btn p-0 m-0">
                                    <img src="" alt="">
                                </button>
                                <button class="btn p-0 m-0">
                                    <img src="" alt="">
                                </button>
                            </div>
                        </div>
                        <div class="inner-text-light">
                            <div class="col table-responsive rounded border-secondary">
                                <table class="table table-bordered border border-2 border-secondary mb-0">
                                    <thead>
                                        <tr style="background-color: #6D7986;">
                                            <th scope="col">Name of Plan</th>
                                            <th scope="col">Limit</th>
                                            <th scope="col">Premium</th>
                                            <th scope="col">Stamp</th>
                                            <th scope="col">Fees</th>
                                            <th scope="col">Sales Tax</th>
                                            <th scope="col">Net Premium</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                            <td>---</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Limit</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="limit" id="limitt" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Fees</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="plan_fee" id="plan_feee" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Sales Tax</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="sales_tax" id="sales_taxx" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Net Premium</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="net_premium" id="net_premiumm" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Gross Premium</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="gross_premium" id="gross_premiumm" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Commission</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="commission" id="commissionn" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Stamp Fee(%)</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="stamp_fee" id="stamp_feee" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 p-3">
                                <div class="input-active">
                                    <div>Commission(%)</div>
                                    <div class="shadow1 p-2 d-flex align-items-center">
                                        <input type="text" name="commission_percent" id="commission_percentt" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-3 ms-auto">
                            <button data-bs-toggle="" data-bs-target="#notif" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">UPDATE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end update plan Model -->

@endsection

@section('script')


<script>
    $(document).ready(function() {
        $(document).on('click', '.editbtn', function() {
            var plan_id = $(this).val();
            alert(plan_id);
            $('#updatemodel').modal('show');
            // Rest of your code

            $.ajax({
                type: "GET",
                url: "{{ route('plan.edit', '') }}/" + plan_id,
                dataType: "json",
                success: function(data) {
                    console.log("cutomer data", data);
                    var plan = data.plan;
                    $('#plan_id').val(plan.id);
                    $('#company_namee').val(plan.company_name);
                    $('#lineofbussiness').val(plan.lineofbussines);
                    $('#plan_name').val(plan.plan_name);
                    $('#limitt').val(plan.limit);
                    $('#plan_feee').val(plan.plan_fee);
                    $('#sales_taxx').val(plan.sales_tax);
                    $('#net_premiumm').val(plan.net_premium);
                    $('#gross_premiumm').val(plan.gross_premium);
                    $('#commissionn').val(plan.commission);
                    $('#stamp_feee').val(plan.stamp_fee);
                    $('#commission_percentt').val(plan.commission_percent);



                }
            });
        });
    });
</script>



<script src="{{asset('{{asset('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('{{asset('script/left-nav.js"></script>
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