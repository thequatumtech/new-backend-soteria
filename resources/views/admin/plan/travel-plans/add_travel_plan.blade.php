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
        -moz-appearance: textfield;
        /* Firefox */
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

    .file-margin {
        text-align: center;
    }

    .file-margin .error {
        text-align: center;
    }
</style>
@endsection

@section('content')
<main class="flex-grow-1 pt-5">
    <div class="container-fluid">
        <div class="header d-flex justify-content-between p-3 py-2 align-items-center">
            <div class="text-white group-title fw-bold">
                @if(isset($plan))
                {{__('messages.plans.edit_travel_plan')}}
                @else
                {{__('messages.plans.add_travel_plan')}}
                @endif
            </div>
        </div>
        <form action="{{route('travel_plan.save')}}" id="add_motor_plan" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="plan_id" value="{{isset($plan)?$plan->id:null}}">
            <input type="hidden" id="form_type" name="form_type" value="{{isset($plan)?'edit':'add'}}">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.insurance_company')}}</div>
                                <select name="insurance_company_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('insurance_company_id') && !isset($plan->insurance_company_id) ? 'selected' : '')}} hidden>--Select--</option>
                                    @foreach($insurance_companies as $single)
                                    <option value="{{$single->id}}" {{(old('insurance_company_id') && old('insurance_company_id') == $single->id) ? 'selected':(isset($plan->insurance_company_id) && $plan->insurance_company_id == $single->id? 'selected':'')}}>{{$single->company_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.line_of_business')}}</div>
                                <input type="text" style="border: none;" disabled value="{{$line_of_businesses}}">
                                {{--
                                <select name="line_of_business_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                    <option value="" disabled {{(!old('line_of_business_id') && !isset($plan->line_of_business_id) ? 'selected' : '')}} hidden>--Select--</option>
                                @foreach($line_of_businesses as $single)
                                <option value="{{$single->id}}" {{(old('line_of_business_id') && old('line_of_business_id') == $single->id) ? 'selected':(isset($plan->line_of_business_id) && $plan->line_of_business_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                @endforeach
                                </select>
                                --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.plan_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="plan_name" style="border: none;" required value="{{old('plan_name')?:($plan->plan_name ?? '')}}">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.policy_period')}}</h3>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.max_days')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="number" id="policy_period" class="form-control number"
                                        name="policy_period" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required max="4745">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.insurance_policy_wording')}}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    {{__('messages.plans.policy_wording_text')}}
                                </div>
                                <textarea name="insurance_policy_text" class="ceditor" id="insurance_policy_text">{{isset($plan) && !empty($plan->insurance_policy_text) ? $plan->insurance_policy_text : ''}}</textarea>
                            </div>
                        </div>
                        <div class="row pt-4 pb-5 g-0 gap-4">
                            <div class="col-12 col-lg">
                                <div> {{__('messages.plans.policy_wording_pdf')}} </div>
                                @if(isset($plan) && $plan->insurance_policy_pdf)
                                <a id="v_insurance_policy_pdf" href="{{$plan->insurance_policy_pdf}}"
                                    target="_blank">View</a>
                                @endif
                                <div class="card border-0 file-upload h-100 pt-2" style="cursor: pointer;">
                                    <div class="py-5 shadow1 d-flex flex-column justify-content-center align-items-center gap-3">
                                        <div>
                                            <img src="{{asset('img/icon-upload.png')}}">
                                        </div>
                                        <input type="file" name="insurance_policy_pdf" id="insurance_policy_pdf" accept="application/pdf">
                                        <div class="file-margin">
                                            <label class="custom-file-label text-center" style="color: #ced4da; font-size: 1rem;">{{__('messages.agents.upload')}}</label>
                                            <label id="insurance_policy_pdf-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_country_restriction')}}</div>
                                <select name="restricted_country_ids[]" id="restricted_country_ids" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($countries as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id,old('restricted_country_ids',[]))}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_city_restriction')}}</div>
                                <select name="restricted_city_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($cities as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id,old('restricted_city_ids',[]))}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_district_restriction')}}</div>
                                <select name="restricted_district_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($districts as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id,old('restricted_district_ids',[]))}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_age_restriction')}}</div>
                                <select name="restricted_age_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($ages as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id,old('restricted_age_ids',[]))}}>{{$single->age}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_dangerous_activities_restriction')}}</div>
                                <select name="restricted_dangerous_activities_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($dangerous_activities as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id,old('restricted_dangerous_activities_ids',[]))}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.geographical_area')}}</div>
                                <select name="geographical_areas_ids" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;">
                                    <option value="" disabled="" selected="" hidden="">--Select--</option>
                                    @foreach($geographical_areas as $single)
                                    <option value="{{$single->id}}" {{$single->id == old('geographical_areas_ids') ? 'selected' : ''}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                        <div class="row">
                            <!-- <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.geographical_area')}}</div>
                                <select name="geographical_areas_ids" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required>
                                    <option value="" disabled="" selected="" hidden="">--Select--</option>
                                    @foreach($geographical_areas as $single)
                                    <option value="{{$single->id}}" {{$single->id == old('geographical_areas_ids') ? 'selected' : ''}}>{{$single->name}}</option>
                                    @endforeach
                                </select>
                            </div> -->

                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('Countries')}}</div>
                                <select name="countries[]" id="countries_select" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($countries as $single)
                                    <option value="{{$single->id}}" {{in_array($single->id, old('countries', [])) ? 'selected' : ''}}>
                                        {{$single->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.add_policy_covers')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.name_of_cover')}}</th>
                                    <th>{{__('messages.plans.limit')}}</th>
                                    <th>{{__('messages.plans.deductible')}}</th>
                                    {{-- <th>{{__('messages.plans.rate')}}</th>
                                    <th>{{__('messages.plans.premium')}}</th> --}}
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-id="0">
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[0][cover_name]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[0][cover_limit]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[0][cover_deductible]" style="border: none;" required>
                                        </div>
                                    </td>
                                    {{-- <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="policy_covers[0][cover_rate]" style="border: none;" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="policy_covers[0][cover_premium]" style="border: none;" class="cover_premium positive_number_only_2_decimal" required>
                                    </div>
                                </td> --}}
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.plan_pricing')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.upto_70_yrs')}}</th>
                                    <th id="th_plan_name"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="Up to 7 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[0][min_days]" value="0">
                                            <input type="hidden" name="plan_pricing[0][max_days]" value="7">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[0][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="8 – 10 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[1][min_days]" value="8">
                                            <input type="hidden" name="plan_pricing[1][max_days]" value="10">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[1][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="11 – 15 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[2][min_days]" value="11">
                                            <input type="hidden" name="plan_pricing[2][max_days]" value="15">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[2][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="16 – 21 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[3][min_days]" value="16">
                                            <input type="hidden" name="plan_pricing[3][max_days]" value="21">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[3][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="22 - 31 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[4][min_days]" value="22">
                                            <input type="hidden" name="plan_pricing[4][max_days]" value="31">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[4][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="32 - 62 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[5][min_days]" value="32">
                                            <input type="hidden" name="plan_pricing[5][max_days]" value="62">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[5][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="63 - 92 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[6][min_days]" value="63">
                                            <input type="hidden" name="plan_pricing[6][max_days]" value="92">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[6][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="93 - 184 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[7][min_days]" value="93">
                                            <input type="hidden" name="plan_pricing[7][max_days]" value="184">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[7][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="185 - 365 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[8][min_days]" value="185">
                                            <input type="hidden" name="plan_pricing[8][max_days]" value="365">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[8][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" style="border: none;" disabled value="366-730 days (Travel Period)">
                                            <input type="hidden" name="plan_pricing[9][min_days]" value="366">
                                            <input type="hidden" name="plan_pricing[9][max_days]" value="730">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="plan_pricing[9][price]" class="" style="border: none;" required>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.add_surcharge_band')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="40%">{{__('messages.plans.age_band')}}</th>
                                    <th width="40%">{{__('messages.plans.surcharge_percentage')}}</th>
                                    <th width="5%"></th>
                                    <th width="15%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[0][age_band]" oninput="this.value = this.value.replace(/[^0-9-]/g, '').replace(/(.*?)-(.*?)-.*/, '$1-$2').replace(/^-/, '');" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[0][surcharge]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h3 class="pt-5">{{__('messages.plans.add_discount_age_band')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="40%">{{__('messages.plans.age_band')}}</th>
                                    <th width="40%">{{__('messages.plans.discount_percentage')}}</th>
                                    <th width="5%"></th>
                                    <th width="15%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[0][age_band]" oninput="this.value = this.value.replace(/[^0-9-]/g, '').replace(/(.*?)-(.*?)-.*/, '$1-$2').replace(/^-/, '');" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[0][discount]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h3 class="pt-5">{{__('messages.plans.premium_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th width="20%">{{__('messages.plans.plan_name')}}</th> --}}
                                    {{-- <th>{{__('messages.plans.limit')}}</th>
                                    <th>{{__('messages.plans.net_premium')}}</th> --}}
                                    <th>{{__('messages.plans.fees')}}</th>
                                    <th>{{__('messages.plans.stamps')}}</th>
                                    <th>{{__('messages.plans.sales_tax')}}</th>
                                    <th>{{__('messages.plans.cbj')}}</th>
                                    <th>{{__('messages.plans.sales_tax_on_cbj')}}</th>
                                    {{-- <th>{{__('messages.plans.gross_premium')}}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    {{-- <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" id="calc_plan_name" style="border: none;" readonly>
                                    </div>
                                </td>
                                <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="limit" maxlength="10" style="border: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="net_premium" id="net_premium" class="calc_gross_premium" style="border: none;" required>
                                    </div>
                                </td> --}}

                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="fees" id="fees" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="stamps" id="stamps" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="sales_tax" id="sales_tax" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="cbj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" class="calc_gross_premium positive_number_only_2_decimal" id="cbj" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="salextaxcbj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" class="calc_gross_premium positive_number_only_2_decimal" id="cbj_sales_tax" required>
                                        </div>
                                    </td>
                                    {{-- <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="gross_premium" id="gross_premium" style="border: none;" readonly required>
                                    </div>
                                </td> --}}
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.commissions_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th>{{__('messages.plans.net_premium')}}</th> --}}
                                    <th>{{__('messages.plans.commission_percentage')}}</th>
                                    {{-- <th>{{__('messages.plans.commission_amount')}}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <input type="hidden" id="net_premium1" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" value="0" readonly>
                                    <input type="hidden" name="commission_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" id="commission_amount" style="border: none;" value="0" readonly>


                                    {{-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="net_premium1" style="border: none;" readonly>
                                        </div>
                                    </td> --}}

                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_percentage" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" id="commission_percentage" class="positive_number_only_2_decimal" style="border: none;" required>
                                        </div>
                                    </td>
                                    {{-- <input type="hidden" name="commission_amount" id="commission_amount" style="border: none;" required value="0"> --}}

                                    {{-- <td>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="commission_amount" id="commission_amount" style="border: none;" required readonly>
                                    </div>
                                </td> --}}

                                </tr>
                            </tbody>
                        </table>

                        <div class="row pt-5">
                            <div class="col-12 col-lg-9"></div>
                            <div class="col-12 col-lg-3">
                                <div class="pt-4 " style="border: none;">
                                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{isset($plan)? __('messages.clients.save') : __('messages.clients.add')}} </button>
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
<script>
    var deleteIconUrl = "{{ asset('img/icon-delete.png') }}";
    $(document).ready(function() {});
</script>
<!-- <script>
    const selectedCityIds = @json($selected_city_ids ?? []);
    const selectedDistrictIds = @json($selected_district_ids ?? []);
</script>

<script>
    $(document).ready(function() {
        // Country → City AJAX
        function loadCities(countryIds, callback) {
            $.ajax({
                url: '{{ route("get.cities.by.countries") }}',
                method: 'POST',
                data: {
                    country_ids: countryIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#restricted_city_ids').empty();
                    $.each(response.cities, function(id, name) {
                        const selected = selectedCityIds.includes(parseInt(id)) ? 'selected' : '';
                        $('#restricted_city_ids').append(`<option value="${id}" ${selected}>${name}</option>`);
                    });
                    $('#restricted_city_ids').trigger('change');

                    if (callback) callback(); // load districts after cities
                }
            });
        }

        // City → District AJAX
        function loadDistricts(cityIds) {
            $.ajax({
                url: '{{ route("get.districts.by.cities") }}',
                method: 'POST',
                data: {
                    city_ids: cityIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#restricted_district_ids').empty();
                    $.each(response.districts, function(id, name) {
                        const selected = selectedDistrictIds.includes(parseInt(id)) ? 'selected' : '';
                        $('#restricted_district_ids').append(`<option value="${id}" ${selected}>${name}</option>`);
                    });
                    $('#restricted_district_ids').trigger('change');
                }
            });
        }

        // On country change → load cities
        $('#restricted_country_ids').on('change', function() {
            const countryIds = $(this).val();
            if (countryIds.length === 0) {
                $('#restricted_city_ids').empty().trigger('change');
                $('#restricted_district_ids').empty().trigger('change');
                return;
            }

            loadCities(countryIds);
        });

        // On city change → load districts
        $('#restricted_city_ids').on('change', function() {
            const cityIds = $(this).val();
            if (cityIds.length === 0) {
                $('#restricted_district_ids').empty().trigger('change');
                return;
            }
            loadDistricts(cityIds);
        });

        // On page load: if countries are selected, auto-load cities & districts
        const initialCountryIds = $('#restricted_country_ids').val();
        if (initialCountryIds.length > 0) {
            loadCities(initialCountryIds, function() {
                // Cities loaded, now load districts
                loadDistricts(selectedCityIds);
            });
        }

    });
</script> -->
<script>
    $(document).ready(function() {
        function fetchCBJ(companyId) {
            if (companyId) {
                $.ajax({
                    url: '{{ route("get.cbj") }}',
                    type: 'GET',
                    data: {
                        insurance_company_id: companyId,
                        line_of_business_id: 10
                    },
                    success: function(response) {
                        $('input[name="cbj"]').val(response.cbj ?? '');
                        $('input[name="salextaxcbj"]').val(response.sales_tax_on_cbj ?? '');
                        $('input[name="fees"]').val(response.insurance_fee ?? '');
                        $('input[name="stamps"]').val(response.stamp ?? '');
                        $('input[name="sales_tax"]').val(response.tax ?? '');
                        $('#commission_percentage').val(response.commission_percentage ?? '');
                    },
                    error: function() {
                        alert('Failed to fetch values.');
                    }
                });
            }
        }

        // On change
        $('select[name="insurance_company_id"]').on('change', function() {
            let companyId = $(this).val();
            fetchCBJ(companyId);
        });

        // Trigger fetch on page load if already selected
        let preselectedCompanyId = $('select[name="insurance_company_id"]').val();
        if (preselectedCompanyId) {
            fetchCBJ(preselectedCompanyId);
        }
    });
    // When geographical area changes, load its countries
    /*$('select[name="geographical_areas_ids"]').on('change', function() {
        var geoAreaId = $(this).val();
        var $countriesSelect = $('#countries_select');

        $countriesSelect.empty().trigger('change');

        if (!geoAreaId) return;

        $.ajax({
            url: '{{ route("get.countries.by.geoarea") }}',
            type: 'GET',
            data: {
                geo_area_id: geoAreaId
            },
            success: function(response) {
                $.each(response.countries, function(index, country) {
                    $countriesSelect.append(
                        '<option value="' + country.id + '">' + country.name + '</option>'
                    );
                });
                $countriesSelect.trigger('change'); // refresh select2
            },
            error: function() {
                alert('Failed to fetch countries.');
            }
        });
    });*/
</script>

<script src="{{asset('js/travel_plan.js?v=1')}}"></script>
@endsection