@extends('layouts.mainlayout')
@section('style')
<style>
    input[type="file"] {
        height: 50px;
        cursor: pointer;
        margin-top: -40px;
        opacity: 0;
        position: relative;
    }
    .file-margin{
        text-align:center;
    }
    .file-margin .error{
        text-align:center;
    }
</style>
@endsection
@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold"> {{__('messages.insurance_company.insurance_company')}} </div>
                @if(is_admin_authorized('insurance_company_add'))
                    <button data-bs-toggle="modal" data-bs-target="#addInsuranceCompany" class="btn pe-0">
                        <img src="{{asset('img/icon-add.png')}}" alt="">
                    </button>
                @endif
            </div>
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>
                        <th> {{__('messages.insurance_company.id')}} </th>
                        <th> {{__('messages.insurance_company.company_name')}} </th>
                        <th> {{__('messages.insurance_company.tax_number')}} </th>
                        <th> {{__('messages.insurance_company.email')}} </th>
                        <th> {{__('messages.insurance_company.claim_email')}} </th>
                        <th> {{__('messages.insurance_company.mobile_number')}} </th>
                        <th class="no-order"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($insurance_companies as $key => $insurance_company)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$insurance_company->company_name}}</td>
                            <td>{{$insurance_company->tax_number}}</td>
                            <td>{{$insurance_company->email}}</td>
                            <td>{{$insurance_company->claim_email}}</td>
                            <td>{{$insurance_company->mobile_number}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    @if(is_admin_authorized('insurance_company_edit'))
                                        <button class="btn p-0 m-0 editbtn" value="{{$insurance_company->id}}">
                                            <img src="{{asset('img/icon-edit.png')}}" alt="">
                                        </button>
                                    @endif
                                    @if(is_admin_authorized('insurance_company_delete'))
                                        <button class="btn p-0 m-0 deletebtn" value="{{$insurance_company->id}}"><img
                                                src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- START ADD Insurance -->
    <div class="modal fade " id="addInsuranceCompany" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{ __('messages.insurance_company.add_insurance_company') }} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <form id="addInsuranceCompanyForm" action="{{route('insurance-company.create')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row p-3"{{-- style="color: #92959A;"--}}>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.company_name')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="company_name" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.company_national_id')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="company_national_id" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.register_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="register_number" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.tax_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="tax_number" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" name="email" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email_1')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" name="email_1" style="border: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email_2')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" name="email_2" style="border: none;">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.claim_email')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" name="claim_email" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.mobile_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="mobile_number" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.telephone_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="telephone_number" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.joining_date')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="date" name="joining_date" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.country')}}</div>
                                        <select name="country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.city')}}</div>
                                        <select name="city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.district')}}</div>
                                        <select name="district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select District</option>
                                            @foreach($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.street_name')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="street_name" style="border: none;" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.building_no')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="building_no" style="border: none;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.ownership_document')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="ownership_document"  >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="ownership_document-error" class="error" for="ownership_document"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.municipality_license')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="municipality_license"  id="">
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="municipality_license-error" class="error" for="municipality_license"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.practice_certificate')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="practice_certificate"  >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="practice_certificate-error" class="error" for="practice_certificate"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.company_tax_certificate')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="company_tax_certificate"   >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="company_tax_certificate-error" class="error" for="company_tax_certificate"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.company_stamp')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="company_stamp"  id="">
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="company_tcompany_stampax_certificate-error" class="error" for="company_stamp"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.authorized_signature')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="authorized_signature"  >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="authorized_signature-error" class="error" for="authorized_signature"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.letterhead')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="letterhead"   >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="letterhead-error" class="error" for="letterhead"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.logo')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="logo"  id="">
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="logo-error" class="error" for="logo"></label>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <h3>{{__('messages.insurance_company.lob')}}</h3>
                                    @foreach($line_of_business as $lob)
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    {{ $lob->name }}
                                                </label>
                                                <input class="form-check-input checkBusiness" type="checkbox" name="line_of_business[]" id="lob{{ $lob->id }}" value="{{ $lob->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-4">
                                    <div>{{__('messages.insurance_company.company_commissions')}}</div>
                                    <table class="table mt-4" id="commissionTable">
                                        <thead>
                                            <tr>
                                                <th>{{__('messages.insurance_company.name')}}</th>
                                                <th>{{__('messages.insurance_company.commission')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <table class="table mt-4" id="commissionTableNew">
                                        <thead>
                                            <tr>
                                                <th>{{__('messages.insurance_company.lob')}}</th>
                                                <th>{{__('messages.insurance_company.insurance_fee')}}</th>
                                                <th>{{__('messages.insurance_company.stamps')}}</th>
                                                <th>{{__('messages.insurance_company.tax')}}</th>
                                                <th>{{__('messages.insurance_company.cbj')}}</th>
                                                <th>{{ __('messages.insurance_company.sales_tax_on_cbj') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4 pt-5">
                                    <div class="col-12 col-lg">
                                        <div> {{__('messages.insurance_company.privacy_policy')}} </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="privacy_policy"  >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="privacy_policy-error" class="error" for="privacy_policy"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.insurance_company.add')}} </button>
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
    <!-- END ADD Vehicle Color -->
    <!-- START Edit & Update Vehicle Color -->
    <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.insurance_company.edit_insurance_company')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form id="editInsuranceCompanyForm" action="{{route('insurance-company.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="insurance_company_id" id="insurance_company_id" value="">
                    <div class="modal-body">
                        <div class="row p-3" {{--style="color: #92959A;"--}}>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.company_name')}} </div>
                                        <input type="hidden" name="insurance_company_id" id="insurance_company_id_new" value="">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="company_name" name="company_name" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.company_national_id')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="company_national_id" name="company_national_id" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.register_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="register_number" name="register_number" style="border: none;" required value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.tax_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="tax_number" name="tax_number" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" id="email" name="email" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email_1')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" id="email_1" name="email_1" style="border: none;" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.email_2')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" id="email_2" name="email_2" style="border: none;" value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.claim_email')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="email" id="claim_email" name="claim_email" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.mobile_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="number" id="mobile_number" name="mobile_number" style="border: none;" required value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.telephone_number')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="number" id="telephone_number" name="telephone_number" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.joining_date')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="date" id="joining_date" name="joining_date" style="border: none;" required value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.country')}}</div>
                                        <select name="country_id" id="country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.city')}}</div>
                                        <select name="city_id" id="city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div>{{__('messages.insurance_company.district')}}</div>
                                        <select name="district_id" id="district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                            <option value="" disabled selected hidden>Select District</option>
                                            @foreach($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.street_name')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="street_name" name="street_name" style="border: none;" required value="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 pt-2">
                                        <div> {{__('messages.insurance_company.building_no')}} </div>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="building_no" name="building_no" style="border: none;" required value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.ownership_document')}} </div>
                                                <a id ="ownership_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="ownership_document" >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.municipality_license')}} </div>
                                            <a id="muncipality_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="municipality_license"  >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.practice_certificate')}} </div>
                                            <a id="practice_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="practice_certificate"  >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.company_tax_certificate')}} </div>
                                                <a id="company_tax_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="company_tax_certificate"   >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.company_stamp')}} </div>
                                            <a id="company_stamp_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="company_stamp"  >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.authorized_signature')}} </div>
                                            <a id="authorized_signature_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="authorized_signature"  >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.letterhead')}} </div>
                                            <a id="letterhead_doc" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="letterhead"   >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.logo')}} </div>
                                            <a id="logo" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="logo"  >
                                                <div>
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div>{{__('messages.insurance_company.lob')}}</div>
                                    @foreach($line_of_business as $lob)
                                        <div class="col-lg-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    {{ $lob->name }}
                                                </label>
                                                <input class="form-check-input checkBusiness" type="checkbox" name="line_of_business[]" id="lob{{ $lob->id }}" value="{{ $lob->id }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-4">
                                    <div>{{__('messages.insurance_company.company_commissions')}}</div>
                                    <table class="table mt-4" id="commissionTable">
                                        <thead>
                                            <tr>
                                                <th>{{__('messages.insurance_company.name')}}</th>
                                                <th>{{__('messages.insurance_company.commission')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <table class="table mt-4" id="commissionTableNew">
                                        <thead>
                                            <tr>
                                                <th>{{__('messages.insurance_company.lob')}}</th>
                                                <th>{{__('messages.insurance_company.insurance_fee')}}</th>
                                                <th>{{__('messages.insurance_company.stamps')}}</th>
                                                <th>{{__('messages.insurance_company.tax')}}</th>
                                                <th>{{__('messages.insurance_company.cbj')}}</th>
                                                <th>{{ __('messages.insurance_company.sales_tax_on_cbj') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Table rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row pt-4 g-0 gap-4">
                                    <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div> {{__('messages.insurance_company.privacy_policy')}} </div>
                                            <a id="privacy_policy" href="" target="_blank">View</a>
                                        </div>
                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                            <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                                <div>
                                                    <img src="{{asset('img/icon-upload.png')}}">
                                                </div>
                                                <input type="file" name="privacy_policy"  >
                                                <div class="file-margin">
                                                    <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">Upload your Document Here</label>
                                                    <label id="privacy_policy-error" class="error" for="privacy_policy"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9"></div>
                                    <div class="col-12 col-lg-3">
                                        <div class="pt-4 " style="border: none;">
                                            <button data-bs-target="#notif" data-bs-toggle="" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;"> {{__('messages.insurance_company.update')}} </button>
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

    <!-- END Edit & Update Vehicle Color -->
    <!-- START Delete Vehicle Color -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5"> {{__('messages.insurance_company.delete_insurance_company')}} </h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('insurance-company.destroy')}}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4> {{__('messages.table_headers.confirm_to_delete_data')}} </h4>
                                        <input type="hidden" id="deleteing_id" name="id">
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
    <!-- END Delete Vehicle Color -->
    <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x w-100">
        <div class="p-3 col-12 col-lg-8 mx-auto">
            <div class="toast align-items-center text-bg-danger border-0 col-12 col-lg-8 w-100" data-bs-delay="3000" role="alert" aria-live="assertive" aria-atomic="true" id="clientSelectToast">
                <div class="d-flex">
                    <div class="toast-body px-5 fw-bold py-3" style="font-size: 1.375rem;">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let checkMobileUrl = "{{ route('insurance-company.check_mobile') }}";
        let checkEmailUrl = "{{ route('insurance-company.check_email') }}";
    </script>

    <script src="{{asset('js/insurance.js?v=1.1')}}"></script>

@endsection

