@extends('layouts.mainlayout')
@section('style')
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
        /* Style for modal content */
        .view-modal-content {
            background-color: #fefefe;
            /*margin: 5% auto;*/
            margin-left: 5%;
            /*padding: 20px;*/
            padding-top: 20px;
            /*border: 1px solid #888;*/
            width: 80%;
        }
        /* Override default error styles */
        .error {
            color: inherit; /* Set the color to inherit */
        }
    </style>
@endsection

@section('content')
    <main class="flex-grow-1 pt-5">
        <div class="container-fluid">
            <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
                <div class="text-white group-title fw-bold">{{$title}}</div>
                <a href="{{$add_route}}" class="btn pe-0">
                    <img src="{{asset('img/icon-add.png')}}" alt="">
                </a>
            </div>

            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <table id="example" class="table table-striped " style="width:100%">
                    <thead>
                    <tr>
                        <th class="no-search">{{__('messages.clients.id')}}</th>
                        <th>{{__('messages.plans.plan_name')}}</th>
                        <th>{{__('messages.plans.insurance_company')}}</th>
                        <th class="no-search">{{__('messages.plans.net_premium')}}</th>
                        <th class="no-order no-search">{{__('messages.clients.action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($plans as $key => $data)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{$data->plan_name}}</td>
                            <td>{{$data->insurance_company->company_name ?? ''}}</td>
                            <td>{{$data->net_premium}}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-evenly align-items-center px-2">
                                    {{--                                <a href="" class="" style="color: #939EAA !important;">More</a>--}}
{{--
                                    <button type="button" class="btn p-0 m-0 btn-custom viewbtn"  value="{{$data->id}}">
                                        <img src="{{asset('img/icon-eye.png')}}" alt="View" title="View">
                                    </button>
--}}
                                    <a href="{{route('office_plan.edit',$data->id)}}" class="btn p-0 m-0" title="Edit">
                                        <img src="{{asset('img/icon-edit.png')}}" alt="">
                                    </a>
                                    <button class="btn p-0 m-0 deletebtn1" value="{{$data->id}}" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- START Delete Plan -->
    <div class="modal fade " id="DeleteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5">{{__('messages.plans.delete_plan')}}</h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>

                <form action="{{route('office_plan.delete')}}" method="post">

                    @csrf
                    <div class="modal-body">
                        <div class="row p-3" style="color: #92959A;">
                            <div class="container">
                                <div class="row pt-5">
                                    <div class="col-12 col-lg-9">
                                        <h4>{{__('messages.clients.delete_confirm')}}</h4>
                                        <input type="hidden" id="deleting_id" name="delete_plan_id">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button data-bs-target="#notif" data-bs-toggle="modal" type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2" style="background-color: #EF7C00;">{{__('messages.clients.delete')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Delete Plan -->

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

    <!-- START View Plan -->
    <div class="modal fade viewmodal" id="ViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white " style="background-color: #104E9E;">
                    <h1 class="modal-title fs-5">{{__('messages.plans.plan_details')}}</h1>
                    <button data-bs-dismiss="modal" class="btn">
                        <img src="{{asset('img/icon-close.svg')}}" alt="">
                    </button>
                </div>
                <div class="row view-modal-content">
                    <p><strong>{{__('messages.agents.first_name')}} : </strong><span id="v_first_name"></span></p>
                    <p><strong>{{__('messages.agents.father_name')}} : </strong><span id="v_father_name"></span></p>
                    <p><strong>{{__('messages.agents.grandfather_name')}} : </strong><span id="v_grandfather_name"></span></p>
                    <p><strong>{{__('messages.agents.surname')}} : </strong><span id="v_surname"></span></p>
                    <p><strong>{{__('messages.clients.language')}} : </strong><span id="v_language"></span></p>
                    <p><strong>{{__('messages.agents.nationality')}} : </strong><span id="v_nationality"></span></p>
                    <p><strong>{{__('messages.agents.id_number')}} : </strong><span id="v_id_number"></span></p>
                    <p><strong>{{__('messages.agents.residence_no')}} : </strong><span id="v_residence_no"></span></p>
                    <p><strong>{{__('messages.agents.birth_date')}} : </strong><span id="v_birth_date"></span></p>
                    <p><strong>{{__('messages.agents.gender')}} : </strong><span id="v_gender"></span></p>
                    <p><strong>{{__('messages.agents.marital_status')}} : </strong><span id="v_marital_status"></span></p>
                    <p><strong>{{__('messages.agents.email')}} : </strong><span id="v_email"></span></p>
                    <p><strong>{{__('messages.agents.mobile_no')}} : </strong><span id="v_mobile_no"></span></p>
                    <h4>{{__('messages.clients.home_address')}}</h4>
                    <p><strong>{{__('messages.agents.country')}} : </strong><span id="v_country"></span></p>
                    <p><strong>{{__('messages.clients.residing_country')}} : </strong><span id="v_residing_country"></span></p>
                    <p><strong>{{__('messages.agents.city')}} : </strong><span id="v_city"></span></p>
                    <p><strong>{{__('messages.agents.district')}} : </strong><span id="v_district"></span></p>
                    <p><strong>{{__('messages.agents.street_name')}} : </strong><span id="v_street_name"></span></p>
                    <p><strong>{{__('messages.agents.building_no')}} : </strong><span id="v_building_no"></span></p>
                    <p><strong>{{__('messages.clients.company_name')}} : </strong><span id="v_company_name"></span></p>
                    <p><strong>{{__('messages.clients.position')}} : </strong><span id="v_position"></span></p>
                    <p><strong>{{__('messages.clients.work_nature')}} : </strong><span id="v_work_nature"></span></p>
                    <p><strong>{{__('messages.clients.company_city')}} : </strong><span id="v_company_city"></span></p>
                    <p><strong>{{__('messages.clients.company_district')}} : </strong><span id="v_company_district"></span></p>
                    <p><strong>{{__('messages.clients.company_street_name')}} : </strong><span id="v_company_street_name"></span></p>
                    <p><strong>{{__('messages.clients.company_building_no')}} : </strong><span id="v_company_building_no"></span></p>
                    <p><strong>{{__('messages.clients.company_contact_no')}} : </strong><span id="v_company_contact_no"></span></p>
                    <p><strong>{{__('messages.agents.id_front')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_front">View</a> </p>
                    <p><strong>{{__('messages.agents.id_back')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_id_back">View</a> </p>
                    <p><strong>{{__('messages.agents.profile_pic')}} : </strong> <a href="javascript:void(0);" target="_blank" id="vv_profile_pic">View</a> </p>
                    <p><strong>{{__('messages.agents.agent_code')}} : </strong><span id="v_agent_code"></span></p>
                    <div class="has-company">
                        <p><strong>{{__('messages.clients.company_name')}} : </strong><span
                                    id="v_client_company_name"></span></p>
                        <p><strong>{{__('messages.clients.company_national_id_no')}} : </strong><span
                                    id="v_client_company_national_id_no"></span></p>
                        <p><strong>{{__('messages.clients.company_registration_no')}} : </strong><span
                                    id="v_client_company_registration_no"></span></p>
                        <h4>{{__('messages.clients.company_address')}}</h4>
                        <p><strong>{{__('messages.agents.country')}} : </strong><span id="v_client_company_country"></span>
                        </p>
                        <p><strong>{{__('messages.agents.city')}} : </strong><span id="v_client_company_city"></span></p>
                        <p><strong>{{__('messages.agents.district')}} : </strong><span
                                    id="v_client_company_district"></span></p>
                        <p><strong>{{__('messages.agents.street_name')}} : </strong><span
                                    id="v_client_company_street_name"></span></p>
                        <p><strong>{{__('messages.agents.building_no')}} : </strong><span
                                    id="v_client_company_building_no"></span></p>
                        <p><strong>{{__('messages.clients.office_no')}} : </strong><span
                                    id="v_client_company_office_no"></span></p>
                        <p><strong>{{__('messages.clients.company_telephone_no')}} : </strong><span
                                    id="v_client_company_company_telephone_no"></span></p>
                        <p><strong>{{__('messages.clients.company_owner_telephone_no')}} : </strong><span
                                    id="v_client_company_company_owner_telephone_no"></span></p>
                        <p><strong>{{__('messages.clients.owner_first_name')}} : </strong><span
                                    id="v_client_company_owner_first_name"></span></p>
                        <p><strong>{{__('messages.clients.owner_father_name')}} : </strong><span
                                    id="v_client_company_owner_father_name"></span></p>
                        <p><strong>{{__('messages.clients.owner_grandfather_name')}} : </strong><span
                                    id="v_client_company_owner_grandfather_name"></span></p>
                        <p><strong>{{__('messages.clients.owner_surname')}} : </strong><span
                                    id="v_client_company_owner_surname"></span></p>
                        <p><strong>{{__('messages.clients.is_partner')}} : </strong><span id="v_is_partner"></span></p>
                        <p><strong>{{__('messages.clients.is_authorized')}} : </strong><span id="v_is_authorized"></span>
                        </p>
                        <p><strong>{{__('messages.clients.authorized_position')}} : </strong><span
                                    id="v_authorized_position"></span></p>
                        <p><strong>{{__('messages.clients.is_authorization_in_registration')}} : </strong><span
                                    id="v_is_authorization_in_registration"></span></p>
                        <p><strong>{{__('messages.clients.issuer_authorization_document')}} : </strong> <a
                                    href="javascript:void(0);" target="_blank"
                                    id="vv_issuer_authorization_document">View</a></p>
                        <p><strong>{{__('messages.clients.ownership_document')}} : </strong> <a href="javascript:void(0);"
                                                                                                target="_blank"
                                                                                                id="vv_ownership_document">View</a>
                        </p>
                        <p><strong>{{__('messages.clients.career_municipality_license')}} : </strong> <a
                                    href="javascript:void(0);" target="_blank" id="vv_career_municipality_license">View</a>
                        </p>
                        <p><strong>{{__('messages.clients.company_tax_certificate')}} : </strong> <a
                                    href="javascript:void(0);" target="_blank" id="vv_company_tax_certificate">View</a></p>
                        <p><strong>{{__('messages.clients.practice_certificate')}} : </strong> <a href="javascript:void(0);"
                                                                                                  target="_blank"
                                                                                                  id="vv_practice_certificate">View</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END View Plan -->


@endsection

@section('script')
    <script src="{{asset('js/office_plan.js')}}"></script>
@endsection

