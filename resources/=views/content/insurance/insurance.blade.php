@extends('layouts.mainlayout')
@section('style')
<link rel="stylesheet" href="{{asset('css/agent.css')}}">
<link rel="stylesheet" href="{{asset('css/insurance.css')}}">
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

    .card-body {
        padding-top: .3rem !important;
        padding-bottom: .3rem !important;
        padding-right: 1rem;
        padding-left: 1rem;
        height: 4rem;
    }
</style>

@endsection

@section('content')

<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">Insurance Companies</div>
            <button class="btn pe-0" data-bs-toggle="modal" data-bs-target="#addComapany">
                <img src="{{asset('img/icon-add.png')}}" alt="">
            </button>
        </div>
        <div class="body py-5 px-4 row g-0 insurance-container1">
            @foreach($insurancedata as $key => $insurancesdata)
            <a href="insurance3.html" class="col-12 col-sm-6 col-md-4 p-3 position-relative btn border-0">
                <div class="insurance-box bg-white py-5 d-flex align-items-center justify-content-center">
                    <!-- <img src="{{asset('img/card-2-big.png')}}" alt="" class="img-fluid"> -->
                    <img src="{{ $insurancesdata['insurance_stamp_company']['src'] }}" alt="" class="img-fluid">
                </div>
                <div class="position-absolute top-0 end-0">
                    <div class="bg-white p-2 rounded-circle shadow">
                        <img src="{{asset('img/icon-card.png')}}" alt="" class="img-fluid p-1 logo-1-black">
                        <img src="{{asset('img/icon-card-active.png')}}" alt="" class="img-fluid p-1 logo-1-blue">
                    </div>
                </div>
            </a>
            @endforeach
            </a>
        </div>
    </div>
</main>
<!-- Add Company Modal -->
<div class="modal fade " id="addComapany" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Add Insurance Company</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('insurance.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> Full Name</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="full_name" id="name" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-2">
                                    <div> License Number </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="text" name="license_number" id="email" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 pt-4">
                                    <div> Full Address </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="address" class="form-control" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Telephone Number</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="telephone_number" id="jtime" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Post Address </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="post_address" class="form-control" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Line of Business </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " name="bussiness_line" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';" required>
                                        <option disabled selected hidden>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Tax ID/No. </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="text" name="tax_id" style="border: none;" required>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Fax Number </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="number" name="fax_number" style="border: none;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div> Stamp of Insurance Company </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="insurance_stamp_company" class="d-none" id="open1" onchange="">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> Signature </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="signature" class="d-none" id="open2">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> Letter Head </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="letter_head" class="d-none" id="open3">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD Company</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- start Update Modal -->
<div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Update Insurance Company</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('insurance.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="insurance_id" id="insurance_id" value="">
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
                                    <div> License Number </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="text" name="license_number" id="license_number" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 pt-4">
                                    <div> Full Address </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="address" id="address" class="form-control" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Telephone Number</div>
                                    <div class="shadow1  p-2 ">
                                        <input type="tel" name="telephone_number" id="telephone_number" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4">
                                    <div>Post Address </div>
                                    <div class="shadow1 p-2">
                                        <input type="text" name="post_address" id="post_address" class="form-control" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Line of Business </div>
                                    <select class="form-select form-select-lg mb-3 shadow1 rounded-0 w-100 " id="bussiness_line" name="bussiness_line" style="border: none; height: 65%; color: #92959A; font-family: 'Nunito';">
                                        <option selected>
                                            <div class="text-light">--Select-- </div>
                                        </option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Tax ID/No. </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="text" id="tax_id" name="tax_id" style="border: none;">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 pt-4">
                                    <div> Fax Number </div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="text" name="fax_number" id="fax_number" style="border: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div> Stamp of Insurance Company </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="insurance_stamp_company" class="d-none" id="1">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> Signature </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="signature" class="d-none" id="2">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div> Letter Head </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="letter_head" class="d-none" id="3">
                                            <div>
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Letter Head Here</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h3>Stamp of Insurance Company</h3>
                                    <table class="table table-striped" id="stamp_edit_file">
                                        <thead>
                                            <tr>
                                                <th>Index</th>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <h3>Signature</h3>
                                    <table class="table table-striped" id="signature_edit_file">
                                        <thead>
                                            <tr>
                                                <th>Index</th>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <h3>Signature</h3>
                                    <table class="table table-striped" id="letterhead_edit_file">
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
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">ADD Company</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end Update Modal -->
<!-- start delete modal -->
<div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-white " style="background-color: #104E9E;">
                <h1 class="modal-title fs-5"> Delete Insurance Company</h1>
                <button data-bs-dismiss="modal" class="btn">
                    <img src="{{asset('img/icon-close.svg')}}" alt="">
                </button>
            </div>

            <form action="{{route('destoryInsurance')}}" method="post">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9">
                                    <h4>Confirm to Delete Data ?</h4>
                                    <input type="hidden" id="deleteing_id" name="delete_insurance_id">
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">YES DELETE </button>
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
<!-- Notification Modal -->
<div class="modal fade modal-xl" id="notif" tabindex="-1" role="dialog" aria-labelledby="notif" aria-hidden="true">
    <div class="modal-dialog h-100 m-0 d-flex align-items-end justify-content-center mx-auto" role="document">
        <div class="modal-content mb-5">
            <div class="modal-body bg-primary">
                <div class="d-flex justify-content-between px-2 px-md-5">
                    <div class="text-white fw-bold py-3" style="font-size: 1.375rem;">
                        New company is successfully added to your dashboard.
                    </div>
                    <button type="button" class="btn text-white" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('input[type="file"]').click(function(e) {
            e.stopImmediatePropagation();
        });

        $('.file-upload').click(function(e) {
            e.preventDefault();
            console.log('Upload', $(this).find('input[type="file"]')[0]);
            let input = $(this).find('input[type="file"]')[0];
            input.click();
        });

        $('.file-upload input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $(this).parents('.file-upload').find('label').html(fileName); // Fixed this line
        });
    });
</script>

<script>
    // delete
    $(document).on('click', '.deletebtn', function() {
        var insurance_id = $(this).val();
        $('#DeleteModal').modal('show');
        $('#deleteing_id').val(insurance_id);
    });



    $(document).ready(function() {
        $(document).on('click', '.editbtn', function() {
            var insurance_id = $(this).val();

            $('#editModal').modal('show');
            // Rest of your code

            $.ajax({
                type: "GET",
                url: "{{ route('insurance.edit', '') }}/" + insurance_id,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    var insurance = data.insurance;
                    $('#insurance_id').val(insurance.id);
                    $('#full_name').val(insurance.full_name);
                    $('#license_number').val(insurance.license_number);
                    $('#address').val(insurance.address);
                    $('#telephone_number').val(insurance.telephone_number);
                    $('#post_address').val(insurance.post_address);
                    $('#bussiness_line').val(insurance.bussiness_line);
                    $('#tax_id').val(insurance.tax_id);
                    $('#fax_number').val(insurance.fax_number);
                    // Assuming you have the insurance object containing the necessary data

                    // Function to generate the table rows based on the file type
                    function generateTableRows(fileType, data) {
                        let rows = '';
                        if (data) {
                            const {
                                name,
                                delete_url,
                                src,
                                download_url
                            } = data;
                            rows += `<tr><td>1</td><td>${name}</td><td><a href="${delete_url}">Delete</a>&nbsp;<a href="${src}" target="_blank">View</a>&nbsp;<a href="${download_url}">Download</a></td></tr>`;
                        }
                        return rows;
                    }

                    // Select the table bodies
                    const stampTableBody = $('#stamp_edit_file tbody');
                    const signatureTableBody = $('#signature_edit_file tbody');
                    const letterheadTableBody = $('#letterhead_edit_file tbody');

                    // Generate table rows for each file type
                    const stampRows = generateTableRows('Stamp', insurance.insurance_stamp_company);
                    const signatureRows = generateTableRows('Signature', insurance.signature);
                    const letterheadRows = generateTableRows('Letterhead', insurance.letter_head);

                    // Update the table bodies with the generated rows
                    stampTableBody.html(stampRows);
                    signatureTableBody.html(signatureRows);
                    letterheadTableBody.html(letterheadRows);








                }
            });
        });
    });
</script>
<script>
    function fu(id) {
        const d = document.getElementById(id)
        d.click();
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