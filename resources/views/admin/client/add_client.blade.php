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
                @if(isset($client))
                    {{__('messages.clients.edit_client')}}
                @else
                {{__('messages.clients.add_client')}}
                    @endif
            </div>
        </div>
        <form action="{{route('client.create')}}" id="add_client" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="client_id" value="{{isset($client)?$client->id:null}}">
            <input type="hidden" id="form_type" value="{{isset($client)?'edit':'add'}}">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.first_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="first_name" style="border: none;" required value="{{old('first_name')?:($client->first_name ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.father_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="father_name" style="border: none;" required value="{{old('father_name')?:($client->father_name ??'')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.grandfather_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="grandfather_name" style="border: none;" required value="{{old('grandfather_name')?:($client->grandfather_name ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.surname')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="surname" style="border: none;" required value="{{old('surname')?:($client->surname ??'')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.choose_language')}}</div>
                                <select name="language" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('language') && !isset($client->language) ? 'selected' : '')}} hidden>--Select--</option>
                                    <option value="en" {{(old('language') && old('language') == 'en') ? 'selected':(isset($client->language) && $client->language == 'en'? 'selected':'')}}>{{__('messages.clients.english')}}</option>
                                    <option value="ar" {{(old('language') && old('language') == 'ar') ? 'selected':(isset($client->language) && $client->language == 'ar'? 'selected':'')}}>{{__('messages.clients.arabic')}}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.nationality')}}</div>
                                <select name="nationality_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('nationality_id') && !isset($client->nationality_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @foreach($nationalities as $single)
                                        <option value="{{$single->id}}" {{(old('nationality_id') && old('nationality_id') == $single->id) ? 'selected':(isset($client->nationality_id) && $client->nationality_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.national_id_number')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="national_id_number" style="border: none;" required value="{{old('national_id_number')?:($client->national_id_number ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.residence_id_number')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="residence_id_number" style="border: none;" required value="{{old('residence_id_number')?:($client->residence_id_number ?? '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.birth_date')}}</div>
                                <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                    <input type="date" name="birth_date" id="birth_date" max="{{\Carbon\Carbon::today()->subYears(18)->format('Y-m-d')}}" style="border: none; font-weight: 330;" required  value="{{old('birth_date')?:($client->birth_date ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.gender')}}</div>
                                <select name="gender" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('gender') && !isset($client->gender) ? 'selected' : '')}} hidden>--Select--</option>
                                    <option value="1" {{(old('gender') && old('gender') == 1) ? 'selected':(isset($client->gender) && $client->gender == 1? 'selected':'')}}>{{__('messages.agents.male')}}</option>
                                    <option value="2" {{(old('gender') && old('gender') == 2) ? 'selected':(isset($client->gender) && $client->gender == 2? 'selected':'')}}>{{__('messages.agents.female')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.marital_status')}}</div>
                                <select name="marital_status" id="marital_status" required class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                    <option value="" selected disabled {{(!old('marital_status') && !isset($client->marital_status) ? 'selected' : '')}} hidden> --Select-- </option>
                                    <option value="1" {{(old('marital_status') && old('marital_status') == 1) ? 'selected':(isset($client->marital_status) && $client->marital_status == 1? 'selected':'')}}>{{__('messages.agents.single')}}</option>
                                    <option value="2" {{(old('marital_status') && old('marital_status') == 2) ? 'selected':(isset($client->marital_status) && $client->marital_status == 2? 'selected':'')}}>{{__('messages.agents.married')}}</option>
                                    <option value="3" {{(old('marital_status') && old('marital_status') == 3) ? 'selected':(isset($client->marital_status) && $client->marital_status == 3? 'selected':'')}}>{{__('messages.agents.divorced')}}</option>
                                    <option value="4" {{(old('marital_status') && old('marital_status') == 4) ? 'selected':(isset($client->marital_status) && $client->marital_status == 4? 'selected':'')}}>{{__('messages.agents.widowed')}}</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.email')}}</div>
                                <div class="shadow1  p-2 bg-body ">
                                    <input type="email" id="email_id" name="email_id" style="border: none;" required value="{{old('email_id')?:($client->email_id ?? '')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.mobile_no')}}</div>
                                <div class="shadow1 p-2">
                                    <input type="number" id="mobile_no" name="mobile_no" required style="border: none;" value="{{old('mobile_no')?:($client->mobile_no ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <h3 class="pt-5">{{__('messages.clients.home_address')}}</h3>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.country')}}</div>
                                <select name="country_id" id="country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" {{(!old('country_id') && !isset($client->country_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($countries as $single)
                                        <option value="{{$single->id}}" {{(old('country_id') && old('country_id') == $single->id) ? 'selected':(isset($client->country_id) && $client->country_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <label class="form-check-label" for="residing_country_same">
                                    <input class="form-check-input checkBusiness" type="checkbox"
                                           name="residing_country_same" id="residing_country_same" value="1"
                                    @if((old('residing_country_same') && (old('residing_country_same') == 1)) || (isset($client->country_id) && isset($client->residing_country_id) && ($client->country_id == $client->residing_country_id)))
                                        {{'checked'}}
                                            @endif
                                    >
                                    {{ __('messages.clients.residing_country_same') }}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.residing_country')}}</div>
                                <select name="residing_country_id" id="residing_country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;"
                                @if((old('residing_country_same') && (old('residing_country_same') == 1)) || (isset($client->country_id) && isset($client->residing_country_id) && ($client->country_id == $client->residing_country_id)))
                                    {{'disabled'}}
                                @endif
                                required >
                                    <option value="" {{(!old('residing_country_id') && !isset($client->residing_country_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($countries as $single)
                                        <option value="{{$single->id}}" {{(old('residing_country_id') && old('residing_country_id') == $single->id) ? 'selected':(isset($client->residing_country_id) && $client->residing_country_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.city')}}</div>
                                <select name="city_id" id="city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" {{(!old('city_id') && !isset($client->city_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($cities as $single)
                                        <option value="{{$single->id}}" {{(old('city_id') && old('city_id') == $single->id) ? 'selected':(isset($client->city_id) && $client->city_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.district')}}</div>
                                <select name="district_id" id="district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" {{(!old('district_id') && !isset($client->district_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($districts as $single)
                                        <option value="{{$single->id}}" {{(old('district_id') && old('district_id') == $single->id) ? 'selected':(isset($client->district_id) && $client->district_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.street_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="street_name" style="border: none;" required value="{{old('street_name')?:($client->street_name ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.agents.building_no')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="building_no" style="border: none;" required value="{{old('building_no')?:($client->building_no ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="company_name" required style="border: none;" value="{{old('company_name')?:($client->company_name ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.position')}}</div>
                                <select name="occupation_id" id="occupation_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" {{(!old('occupation_id') && !isset($client->occupation_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($occupations as $single)
                                        <option value="{{$single->id}}" {{(old('occupation_id') && old('occupation_id') == $single->id) ? 'selected':(isset($client->occupation_id) && $client->occupation_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.work_nature')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="work_nature" required style="border: none;" value="{{old('work_nature')?:($client->work_nature ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_city')}}</div>
                                <select name="company_city_id" id="company_city_id" required class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                    <option value="" {{(!old('company_city_id') && !isset($client->company_city_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($cities as $single)
                                        <option value="{{$single->id}}" {{(old('company_city_id') && old('company_city_id') == $single->id) ? 'selected':(isset($client->company_city_id) && $client->company_city_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_district')}}</div>
                                <select name="company_district_id" id="company_district_id" required class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                    <option value="" {{(!old('company_district_id') && !isset($client->company_district_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                    @foreach($districts as $single)
                                        <option value="{{$single->id}}" {{(old('company_district_id') && old('company_district_id') == $single->id) ? 'selected':(isset($client->company_district_id) && $client->company_district_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_street_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="company_street_name" required style="border: none;" value="{{old('company_street_name')?:($client->company_street_name ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_building_no')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="company_building_no" required style="border: none;" value="{{old('company_building_no')?:($client->company_building_no ?? '')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.company_contact_no')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="number" name="company_contact_no" style="border: none;" required value="{{old('company_contact_no')?:($client->company_contact_no ?? '')}}">
                                </div>
                            </div>
                        </div>

                        <div class="row pt-4 g-0 gap-4">
                            <div class="col-12 col-lg">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div> {{__('messages.agents.id_front')}} </div>
                                    @if(isset($client) && isset($client->id_front))
                                        <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->id_front}}" target="_blank">View</a>
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
                                    @if(isset($client) && isset($client->id_back))
                                        <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->id_back}}" target="_blank">View</a>
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
                                    @if(isset($client) && isset($client->profile_pic))
                                        <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->profile_pic}}"
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
                                <div>{{__('messages.clients.agent_id')}}</div>
                                <div class="shadow1  p-2 " style="padding-bottom: 2.5rem;">
                                    <input type="number" name="agent_id" id="agent_id"
                                           style="border: none; font-weight: 330;" autocomplete="off" value="{{old('agent_id')?:($client->agent?->agent_code ?? '')}}">
                                </div>
                            </div>
                        </div>

                        @if(!isset($client))
                        <h3 class="pt-4">{{__('messages.clients.create_password')}}</h3>
                        @else
                            <h3 class="pt-4">{{__('messages.clients.change_password')}}</h3>
                        @endif

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.password')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="password" name="password" id="password" style="border: none;" autocomplete="off" value="{{old('password')?:''}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.clients.conf_password')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="password" name="conf_password" id="conf_password" style="border: none;" value="{{old('conf_password')?:''}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                <label class="form-check-label">
                                    {{__('messages.clients.add_company')}}
                                </label>
                                <div class="radio-class">
                                    <input class="form-check-input has_company" type="radio"
                                           name="has_company" id="has_company_yes" value="1"
                                    @if((old('has_company') && (old('has_company') == 1)) || (isset($client->has_company) && $client->has_company == 1))
                                        {{'checked'}}
                                            @endif
                                    >
                                    <label class="form-check-label radio-label" for="has_company_yes">
                                        {{ __('messages.clients.yes') }}
                                    </label>

                                    <input class="form-check-input has_company" type="radio"
                                           name="has_company" id="has_company_no" value="2"
                                    @if((old('has_company') && (old('has_company') == 2)) || (isset($client->has_company) && $client->has_company == 2))
                                        {{'checked'}}
                                            @endif
                                    >
                                    <label class="form-check-label radio-label" for="has_company_no">
                                        {{ __('messages.clients.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{--START has company--}}
                        <div id="client_has_company">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_name" id="client_company_name" style="border: none;"
                                               value="{{old('client_company_name')?:($client->company->client_company_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_national_id_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_registered_national_id_no" id="client_company_registered_national_id_no" style="border: none;"
                                               value="{{old('client_company_registered_national_id_no')?:($client->company->client_company_registered_national_id_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_registration_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_registration_no" id="client_company_registration_no" style="border: none;"
                                               value="{{old('client_company_registration_no')?:($client->company->client_company_registration_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <h3 class="pt-5">{{__('messages.clients.company_address')}}</h3>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.country')}}</div>
                                    <select name="client_company_country_id" id="client_company_country_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="" {{(!old('client_company_country_id') && !isset($client->company->client_company_country_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}" {{(old('client_company_country_id') && old('client_company_country_id') == $single->id) ? 'selected':(isset($client->company->client_company_country_id) && $client->company->client_company_country_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.city')}}</div>
                                    <select name="client_company_city_id" id="client_company_city_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="" {{(!old('client_company_city_id') && !isset($client->company->client_company_city_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}" {{(old('client_company_city_id') && old('client_company_city_id') == $single->id) ? 'selected':(isset($client->company->client_company_city_id) && $client->company->client_company_city_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.district')}}</div>
                                    <select name="client_company_district_id" id="client_company_district_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;">
                                        <option value="" {{(!old('client_company_district_id') && !isset($client->company->client_company_district_id) ? 'selected' : '')}} disabled hidden> --Select-- </option>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}" {{(old('client_company_district_id') && old('client_company_district_id') == $single->id) ? 'selected':(isset($client->company->client_company_district_id) && $client->company->client_company_district_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.street_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_street_name" id="client_company_street_name" style="border: none;" value="{{old('client_company_street_name')?:($client->company->client_company_street_name ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.agents.building_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_building_no" id="client_company_building_no" style="border: none;" value="{{old('client_company_building_no')?:($client->company->client_company_building_no ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.office_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_office_no" id="client_company_office_no" style="border: none;" value="{{old('client_company_building_no')?:($client->company->client_company_office_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_telephone_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="number" id="client_company_telephone_no" name="client_company_telephone_no" style="border: none;" value="{{old('client_company_telephone_no')?:($client->company->client_company_telephone_no ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.company_owner_telephone_no')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="number" id="client_company_owner_telephone_no" name="client_company_owner_telephone_no" style="border: none;" value="{{old('client_company_owner_telephone_no')?:($client->company->client_company_owner_telephone_no ?? '')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.owner_first_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_owner_first_name" id="client_company_owner_first_name" style="border: none;" value="{{old('client_company_owner_first_name')?:($client->company->client_company_owner_first_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.owner_father_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_owner_father_name" id="client_company_owner_father_name" style="border: none;" value="{{old('client_company_owner_father_name')?:($client->company->client_company_owner_father_name ??'')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.owner_grandfather_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_owner_grandfather_name" id="client_company_owner_grandfather_name" style="border: none;" value="{{old('client_company_owner_grandfather_name')?:($client->company->client_company_owner_grandfather_name ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.owner_surname')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="client_company_owner_surname" id="client_company_owner_surname" style="border: none;" value="{{old('client_company_owner_surname')?:($client->company->client_company_owner_surname ??'')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                    <label class="form-check-label">
                                        {{__('messages.clients.is_partner')}}
                                    </label>
                                    <div class="radio-class">
                                        <input class="form-check-input is_partner" type="radio"
                                               name="is_partner" id="is_partner_yes" value="1"
                                        @if((old('is_partner') && (old('is_partner') == 1)) || (isset($client->company->is_partner) && $client->company->is_partner == 1))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_partner_yes">
                                            {{ __('messages.clients.yes') }}
                                        </label>

                                        <input class="form-check-input is_partner" type="radio"
                                               name="is_partner" id="is_partner_no" value="2"
                                        @if((old('is_partner') && (old('is_partner') == 2)) || (isset($client->company->is_partner) && $client->company->is_partner == 2))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_partner_no">
                                            {{ __('messages.clients.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                    <label class="form-check-label">
                                        {{__('messages.clients.is_authorized')}}
                                    </label>
                                    <div class="radio-class">
                                        <input class="form-check-input is_authorized" type="radio"
                                               name="is_authorized" id="is_authorized_yes" value="1"
                                        @if((old('is_authorized') && (old('is_authorized') == 1)) || (isset($client->company->is_authorized) && $client->company->is_authorized == 1))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_authorized_yes">
                                            {{ __('messages.clients.yes') }}
                                        </label>

                                        <input class="form-check-input is_authorized" type="radio"
                                               name="is_authorized" id="is_authorized_no" value="2"
                                        @if((old('is_authorized') && (old('is_authorized') == 2)) || (isset($client->company->is_authorized) && $client->company->is_authorized == 2))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_authorized_no">
                                            {{ __('messages.clients.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row authorized_position">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.clients.authorized_position')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="authorized_position" id="authorized_position" style="border: none;" value="{{old('authorized_position')?:($client->company->authorized_position ??'')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-12 pt-4 d-flex flex-column">
                                    <label class="form-check-label">
                                        {{__('messages.clients.is_authorization_in_registration')}}
                                    </label>
                                    <div class="radio-class">
                                        <input class="form-check-input is_authorization_in_registration" type="radio"
                                               name="is_authorization_in_registration" id="is_authorization_in_registration_yes" value="1"
                                        @if((old('is_authorization_in_registration') && (old('is_authorization_in_registration') == 1)) || (isset($client->company->is_authorization_in_registration) && $client->company->is_authorization_in_registration == 1))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_authorization_in_registration_yes">
                                            {{ __('messages.clients.yes') }}
                                        </label>

                                        <input class="form-check-input is_authorization_in_registration" type="radio"
                                               name="is_authorization_in_registration" id="is_authorization_in_registration_no" value="2"
                                        @if((old('is_authorization_in_registration') && (old('is_authorization_in_registration') == 2)) || (isset($client->company->is_authorization_in_registration) && $client->company->is_authorization_in_registration == 2))
                                            {{'checked'}}
                                                @endif
                                        >
                                        <label class="form-check-label radio-label" for="is_authorization_in_registration_no">
                                            {{ __('messages.clients.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-4 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.clients.issuer_authorization_document')}} </div>
                                        @if(isset($client) && isset($client->company->issuer_authorization_document))
                                            <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->company->issuer_authorization_document}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="issuer_authorization_document"
                                                   id="issuer_authorization_document" accept="image/*,application/pdf">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center"
                                                       style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="issuer_authorization_document-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.clients.ownership_document')}} </div>
                                        @if(isset($client) && isset($client->company->ownership_document))
                                             <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->company->ownership_document}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="ownership_document" accept="image/*,application/pdf"
                                                   id="ownership_document">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center"
                                                       style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="ownership_document-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.clients.career_municipality_license')}}@if(isset($client) && isset($client->company->career_municipality_license)) @else<br/>&nbsp;@endif</div>
                                        @if(isset($client) && isset($client->company->career_municipality_license))
                                               <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->company->career_municipality_license}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="career_municipality_license" accept="image/*,application/pdf"
                                                   id="career_municipality_license">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center"
                                                       style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="career_municipality_license-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row pt-4 mt-5 g-0 gap-4">
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.clients.company_tax_certificate')}}</div>
                                        @if(isset($client) && isset($client->company->company_tax_certificate))
                                             <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->company->company_tax_certificate}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="company_tax_certificate" accept="image/*,application/pdf"
                                                   id="company_tax_certificate">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center"
                                                       style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="company_tax_certificate-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div> {{__('messages.clients.practice_certificate')}} </div>
                                        @if(isset($client) && isset($client->company->practice_certificate))
                                            <a href="{{asset('uploads/clients').'/'.$client->id.'/'.$client->company->practice_certificate}}"
                                               target="_blank">View</a>
                                        @endif
                                    </div>
                                    <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                        <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                            <div>
                                                <img src="{{asset('img/icon-upload.png')}}">
                                            </div>
                                            <input type="file" name="practice_certificate" accept="image/*,application/pdf"
                                                   id="practice_certificate">
                                            <div class="file-margin">
                                                <label class="custom-file-label text-center"
                                                       style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                                <label id="practice_certificate-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--END has company--}}
                        <div class="row pt-5">
                            <div class="col-12 col-lg-9"></div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{isset($client)? __('messages.clients.save') : __('messages.clients.add')}} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
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
        let checkMobileUrl = "{{ route('client.check_mobile') }}";
        let checkEmailUrl = "{{ route('client.check_email') }}";
    </script>
<script src="{{asset('js/client.js?v=1')}}"></script>
@endsection

