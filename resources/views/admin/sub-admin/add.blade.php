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
    /* Prevent number input spinner */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield; /* Firefox */
    }

    .radio-class {
        float: left;
        clear: none;
    }

    .radio-label {
        float: left;
        clear: none;
        display: block;
        padding: 0px 1em 0px 8px;
    }

    input[type=radio],
    input.radio {
        float: left;
        clear: none;
        margin: 2px 0 0 2px;
    }
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
                <div class="text-white group-title fw-bold">
                    @if(isset($admin))
                        {{__('messages.sub_admins.edit')}}
                    @else
                        {{__('messages.sub_admins.add')}}
                    @endif
                </div>
            </div>
            <form action="{{route('sub_admin.create')}}" id="add_client" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sub_admin_id" value="{{ isset($admin) ? encrypt($admin->id) : '' }}">
                <input type="hidden" id="form_type" value="{{isset($admin) ? 'edit' : 'add'}}">
                <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.first_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="first_name" style="border: none;" required value="{{old('first_name') ?: ($admin->first_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.father_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="second_name" style="border: none;" required value="{{old('second_name') ?: ($admin->second_name ?? '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.grandfather_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="third_name" style="border: none;" required value="{{old('third_name') ?: ($admin->third_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.surname')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="last_name" style="border: none;" required value="{{old('last_name') ?: ($admin->last_name ?? '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.choose_language')}}</div>
                                    <select name="language" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled {{(!old('language') && !isset($admin->language) ? 'selected' : '')}} hidden>--Select--</option>
                                        <option value="en" {{(old('language') && old('language') == 'en') ? 'selected' : (isset($admin->language) && $admin->language == 'en' ? 'selected' : '')}}>{{__('messages.clients.english')}}</option>
                                        <option value="ar" {{(old('language') && old('language') == 'ar') ? 'selected' : (isset($admin->language) && $admin->language == 'ar' ? 'selected' : '')}}>{{__('messages.clients.arabic')}}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.nationality')}}</div>
                                    <select name="nationality_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled {{(!old('nationality_id') && !isset($admin->nationality_id) ? 'selected' : '')}} hidden>--Select--</option>
                                        @foreach($nationalities as $single)
                                            <option value="{{$single->id}}" {{(old('nationality_id') && old('nationality_id') == $single->id) ? 'selected' : (isset($admin->nationality_id) && $admin->nationality_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.national_id_number')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="national_id_no" style="border: none;" required value="{{old('national_id_no') ?: ($admin->national_id_no ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.residence_id_number')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="residence_id_no" style="border: none;" required value="{{old('residence_id_no') ?: ($admin->residence_id_no ?? '')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.birth_date')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="date" name="birth_date" id="birth_date" max="{{\Carbon\Carbon::today()->subYears(18)->format('Y-m-d')}}" style="border: none; font-weight: 330;" required  value="{{old('birth_date') ?: ($admin->birth_date ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.gender')}}</div>
                                    <select name="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled {{(!old('gender') && !isset($admin->gender) ? 'selected' : '')}} hidden>--Select--</option>
                                        <option value="1" {{(old('gender') && old('gender') == 1) ? 'selected' : (isset($admin->gender) && $admin->gender == 1 ? 'selected' : '')}}>{{__('messages.agents.male')}}</option>
                                        <option value="2" {{(old('gender') && old('gender') == 2) ? 'selected' : (isset($admin->gender) && $admin->gender == 2 ? 'selected' : '')}}>{{__('messages.agents.female')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.email')}}</div>
                                    <div class="shadow1  p-2 bg-body ">
                                        <input type="email" id="email" name="email" style="border: none;" required value="{{old('email') ?: ($admin->email ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.mobile_no')}}</div>
                                    <div class="shadow1 p-2">
                                        <input type="number" id="mobile_no" name="mobile_no" required style="border: none;" value="{{old('mobile_no') ?: ($admin->mobile_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <h3 class="pt-5">{{__('messages.clients.home_address')}}</h3>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.country')}}</div>
                                    <select name="country_id" id="country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" {{(!old('country_id') && !isset($admin->country_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}" {{(old('country_id') && old('country_id') == $single->id) ? 'selected' : (isset($admin->country_id) && $admin->country_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.residing_country')}}</div>
                                    <select name="residing_country_id" id="residing_country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required >
                                        <option value="" {{(!old('residing_country_id') && !isset($admin->residing_country_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}" {{(old('residing_country_id') && old('residing_country_id') == $single->id) ? 'selected' : (isset($admin->residing_country_id) && $admin->residing_country_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.city')}}</div>
                                    <select name="city_id" id="city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" {{(!old('city_id') && !isset($admin->city_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}" {{(old('city_id') && old('city_id') == $single->id) ? 'selected' : (isset($admin->city_id) && $admin->city_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.district')}}</div>
                                    <select name="district_id" id="district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" {{(!old('district_id') && !isset($admin->district_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}" {{(old('district_id') && old('district_id') == $single->id) ? 'selected' : (isset($admin->district_id) && $admin->district_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.street_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="street_name" style="border: none;" required value="{{old('street_name') ?: ($admin->street_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.building_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="building_no" style="border: none;" required value="{{old('building_no') ?: ($admin->building_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="company_name" required style="border: none;" value="{{old('company_name') ?: ($admin->company_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.position')}}</div>
                                    <select name="occupation_id" id="occupation_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" {{(!old('occupation_id') && !isset($admin->occupation_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($occupations as $single)
                                            <option value="{{$single->id}}" {{(old('occupation_id') && old('occupation_id') == $single->id) ? 'selected' : (isset($admin->occupation_id) && $admin->occupation_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.work_nature')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="work_nature" required style="border: none;" value="{{old('work_nature') ?: ($admin->work_nature ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.city')}}</div>
                                    <select name="company_city_id" id="company_city_id" required class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="" {{(!old('company_city_id') && !isset($admin->company_city_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}" {{(old('company_city_id') && old('company_city_id') == $single->id) ? 'selected' : (isset($admin->company_city_id) && $admin->company_city_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.district')}}</div>
                                    <select name="company_district_id" id="company_district_id" required class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="" {{(!old('company_district_id') && !isset($admin->company_district_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}" {{(old('company_district_id') && old('company_district_id') == $single->id) ? 'selected' : (isset($admin->company_district_id) && $admin->company_district_id == $single->id ? 'selected' : '')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.street_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="company_street_name" required style="border: none;" value="{{old('company_street_name') ?: ($admin->company_street_name ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.building_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="company_building_no" required style="border: none;" value="{{old('company_building_no') ?: ($admin->company_building_no ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_contact_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="number" name="company_contact_no" style="border: none;" required value="{{old('company_contact_no') ?: ($admin->company_contact_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.agents.id_front')}} </div>
                                        @if(isset($admin) && isset($admin->id_front))
                                            <a href="{{asset('uploads/admins') . '/' . $admin->id . '/' . $admin->id_front}}" target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_front" id="id_front" accept="image/*">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="id_front-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.agents.id_back')}} </div>
                                        @if(isset($admin) && isset($admin->id_back))
                                            <a href="{{asset('uploads/admins') . '/' . $admin->id . '/' . $admin->id_back}}" target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="id_back" id="id_back" accept="image/*">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="id_back-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div> {{__('messages.agents.profile_pic')}}<br/>&nbsp; </div>
                                        @if(isset($admin) && isset($admin->profile_pic))
                                            <a href="{{asset('uploads/admins') . '/' . $admin->id . '/' . $admin->profile_pic}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>

                                        <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="profile_pic-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-5">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.sub_admins.admin_id')}}</div>
                                    <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                        <input type="number" name="admin_id" id="admin_id" readonly
                                               style="border: none; font-weight: 330;" autocomplete="off" value="{{str_pad(isset($admin) ? $admin->admin_id : $next_admin_id, 4, '0', STR_PAD_LEFT)}}">
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->is_super_admin == 1 || in_array('sub_admin.change_pass', json_decode(auth()->user()->authorized_routes, 1)))
                            @if(!isset($admin))
                                <h3 class="pt-4">{{__('messages.clients.create_password')}}</h3>
                            @else
                                <h3 class="pt-4">{{__('messages.clients.change_password')}}</h3>
                            @endif

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.password')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="password" name="password" id="password" style="border: none;" autocomplete="off" value="{{old('password') ?: ''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.conf_password')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="password" name="conf_password" id="conf_password" style="border: none;" value="{{old('conf_password') ?: ''}}">
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(auth()->user()->is_super_admin == 1 || in_array('sub_admin.authorization', json_decode(auth()->user()->authorized_routes, 1)))
                                <div class="row mt-4">
                                    <h3>{{__('messages.sub_admins.authorization')}}</h3>

                                    @php
    $skip_permissions = [
        'add_insurance_company',
        'add_coupon',
        'edit_coupon',
        'delete_coupon',
        'add_supervisor',
        'edit_supervisor',
        'delete_supervisor',
        'suspend_supervisor',
        'add_plan',
        'edit_plan',
        'send_message',
        'renew_policy',
        'cancel_policy',
        'add_user',
        'edit_user',
        'delete_user',
    ];
                                    @endphp


                                @foreach(__('messages.sub_admin_authorizations') as $key => $value)

                                    @if(in_array($key, $skip_permissions))
                                        @continue
                                    @endif
                                            <div class="col-lg-6">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        {{ $value }}
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" name="authorized_routes[]"
                                                           @if(isset($authorized_routes) && in_array($key, $authorized_routes)) checked @endif
                                                           value="{{$key}}">
                                                </div>
                                            </div>
                                @endforeach
                                </div>
                            @endif
                            <div class="row pt-5">
                                <div class="col-12 col-lg-9"></div>
                                <div class="col-12 col-lg-3">
                                    <div class="pt-4 " style="border: none;">
                                        <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{isset($admin) ? __('messages.clients.save') : __('messages.clients.add')}} </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

@endsection

@section('script')
<script src="{{asset('js/sub_admin.js')}}"></script>
@endsection

