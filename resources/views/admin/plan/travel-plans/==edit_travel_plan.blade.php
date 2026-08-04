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
                {{__('messages.plans.edit_travel_plan')}}
            </div>
        </div>
        <form action="{{route('travel_plan.save')}}" id="add_motor_plan" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="plan_id" value="{{isset($plan)?$plan->id:null}}">
            <input type="hidden" id="form_type" name="form_type" value="edit">
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
                                <input type="text" style="border: none;" disabled value="{{$plan->line_of_business->name}}">
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
                                        name="policy_period" style="border: none;" required max="4745" value="{{$plan->policy_period}}">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.insurance_policy_wording')}}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    {{__('messages.plans.policy_wording_text')}}
                                </div>
                                <textarea name="insurance_policy_text" id="insurance_policy_text">{{isset($plan) && !empty($plan->insurance_policy_text) ? $plan->insurance_policy_text : ''}}</textarea>
                            </div>
                        </div>
                        <div class="row pt-4 pb-5 g-0 gap-4">
                            <div class="col-12 col-lg">
                                <div> {{__('messages.plans.policy_wording_pdf')}} </div>
                                @if(isset($plan) && $plan->insurance_policy_pdf)
                                <a id="v_insurance_policy_pdf" href="{{asset('uploads/insurance_plans').'/'.$plan->id.'/'.$plan->insurance_policy_pdf}}"
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
                                <select name="restricted_country_ids[]" multiple id="restricted_country_ids" class="select2 form-select">
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ in_array($country->id, $selected_country_ids) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_city_restriction')}}</div>
                                {{-- <select name="restricted_city_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple required>
                                    @foreach($cities as $single)
                                        <option value="{{$single->id}}"
                                {{ in_array($single->id, old('restricted_city_ids', !empty($plan->restricted_city_ids) ? json_decode($plan->restricted_city_ids) : [])) ? 'selected' : '' }}>
                                {{$single->name}}
                                </option>
                                @endforeach
                                </select> --}}
                                <select name="restricted_city_ids[]" multiple id="restricted_city_ids" class="select2 form-select">
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ in_array($city->id, $selected_city_ids) ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_district_restriction')}}</div>
                                {{-- <select name="restricted_district_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple required>
                                    @foreach($districts as $single)
                                        <option value="{{$single->id}}"
                                {{ in_array($single->id, old('restricted_district_ids', !empty($plan->restricted_district_ids) ? json_decode($plan->restricted_district_ids) : [])) ? 'selected' : '' }}>
                                {{$single->name}}
                                </option>
                                @endforeach
                                </select> --}}
                                <select name="restricted_district_ids[]" multiple id="restricted_district_ids" class="select2 form-select">
                                    @foreach($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ in_array($district->id, $selected_district_ids) ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.policy_holder_age_restriction')}}</div>
                                <select name="restricted_age_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                    @foreach($ages as $single)
                                    <option value="{{$single->id}}"
                                        {{ in_array($single->id, old('restricted_age_ids', !empty($plan->restricted_age_ids) ? json_decode($plan->restricted_age_ids) : [])) ? 'selected' : '' }}>
                                        {{$single->age}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.geographical_area')}}</div>
                                <select name="geographical_areas_ids" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;">
                                    <option value="" disabled="" selected="" hidden="">--Select--</option>
                                    @foreach($geographical_areas as $single)
                                    <option value="{{$single->id}}" {{$single->id == old('geographical_areas_ids',$plan->geographical_areas_ids) ? 'selected' : ''}}>{{$single->name}}</option>
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
                                    <th>{{__('messages.plans.rate')}}</th>
                                    <th>{{__('messages.plans.premium')}}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('policy_covers'))
                                @foreach(old('policy_covers') as $index => $policyCover)
                                <tr data-id="{{$index}}">
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_name]" value="{{ old('policy_covers.'.$index.'.cover_name') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ old('policy_covers.'.$index.'.cover_limit') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_deductible]" value="{{ old('policy_covers.'.$index.'.cover_deductible') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{$index}}][cover_rate]" value="{{ old('policy_covers.'.$index.'.cover_rate') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{$index}}][cover_premium]" value="{{ old('policy_covers.'.$index.'.cover_premium') }}" style="border: none;" class="cover_premium positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{count(old('policy_covers', []))}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif($plan->policy_covers->count() > 0)
                                @foreach($plan->policy_covers as $index => $single)
                                <tr data-id="{{$index}}">
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_name]" value="{{ $single->cover_name }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ number_format($single->cover_limit) }}" style="border: none;" required>
                                            {{-- <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ $single->cover_limit }}" style="border: none;" required> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_deductible]" value="{{ $single->cover_deductible }}" style="border: none;" required>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_rate]" value="{{ $single->cover_rate }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_premium]" value="{{ $single->cover_premium }}" class="cover_premium" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{$plan->policy_covers->count()}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif(count(old('policy_covers', [])) == 0)
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
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.plan_pricing')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.upto_70_yrs')}}</th>
                                    <th id="th_plan_name">{{$plan->plan_name}}</th>
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
                                            <input type="text" name="plan_pricing[0][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[0]->price}}">
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
                                            <input type="text" name="plan_pricing[1][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[1]->price}}">
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
                                            <input type="text" name="plan_pricing[2][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[2]->price}}">
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
                                            <input type="text" name="plan_pricing[3][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[3]->price}}">
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
                                            <input type="text" name="plan_pricing[4][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[4]->price}}">
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
                                            <input type="text" name="plan_pricing[5][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[5]->price}}">
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
                                            <input type="text" name="plan_pricing[6][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[6]->price}}">
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
                                            <input type="text" name="plan_pricing[7][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[7]->price}}">
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
                                            <input type="text" name="plan_pricing[8][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[8]->price}}">
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
                                            <input type="text" name="plan_pricing[9][price]" class="positive_number_only_2_decimal" style="border: none;" required value="{{$plan->pricing_schedule[9]->price}}">
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
                                @if (old('surcharge_band'))
                                @foreach(old('surcharge_band') as $index => $surchargeBand)
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[{{ $index }}][age_band]"
                                                value="{{ old('surcharge_band.'.$index.'.age_band') }}"
                                                style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[{{ $index }}][surcharge]"
                                                value="{{ old('surcharge_band.'.$index.'.surcharge') }}"
                                                style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img
                                                src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{count(old('policy_covers', []))}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif($plan->surcharge_bands->count() > 0)
                                @foreach($plan->surcharge_bands as $index => $single)
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[{{ $index }}][age_band]"
                                                value="{{$single->min_age .'-'.$single->max_age}}"
                                                style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[{{ $index }}][surcharge]"
                                                value="{{$single->surcharge}}"
                                                style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img
                                                src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{$plan->surcharge_bands->count()}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[0][age_band]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="surcharge_band[0][surcharge]" style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3 class="pt-5">{{ __('messages.plans.add_discount_age_band') }}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="40%">{{ __('messages.plans.age_band') }}</th>
                                    <th width="40%">{{ __('messages.plans.discount_percentage') }}</th>
                                    <th width="5%"></th>
                                    <th width="15%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('discount_band'))
                                @foreach(old('discount_band') as $index => $discountBand)
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[{{ $index }}][age_band]"
                                                value="{{ old('discount_band.'.$index.'.age_band') }}"
                                                style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[{{ $index }}][discount]"
                                                value="{{ old('discount_band.'.$index.'.discount') }}"
                                                style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete">
                                            <img src="{{ asset('img/icon-delete.png') }}" alt="">
                                        </button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{ count(old('discount_band', [])) }}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif($plan->discount_bands->count() > 0)
                                @foreach($plan->discount_bands as $index => $single)
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[{{ $index }}][age_band]"
                                                value="{{ $single->min_age . '-' . $single->max_age }}"
                                                style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[{{ $index }}][discount]"
                                                value="{{ $single->discount }}"
                                                style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete">
                                            <img src="{{ asset('img/icon-delete.png') }}" alt="">
                                        </button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{ $plan->discount_bands->count() }}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[0][age_band]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="discount_band[0][discount]" style="border: none;" class="positive_number_only_2_decimal" required>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn p-0 m-0 deletebtn" title="Delete">
                                            <img src="{{ asset('img/icon-delete.png') }}" alt="">
                                        </button>
                                    </td>
                                    <td rowspan="1" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.premium_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th width="20%">{{__('messages.plans.plan_name')}}</th> -->
                                    <!-- <th>{{__('messages.plans.limit')}}</th> -->
                                    <!-- <th>{{__('messages.plans.net_premium')}}</th> -->
                                    <th>{{__('messages.plans.fees')}}</th>
                                    <th>{{__('messages.plans.stamps')}}</th>
                                    <th>{{__('messages.plans.sales_tax')}}</th>
                                    <!-- <th>cbj</th> -->
                                    <!-- <th>{{__('messages.plans.gross_premium')}}</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="calc_plan_name" style="border: none;" value="{{old('plan_name',($plan->plan_name ?? ''))}}" readonly>
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="calc_limit" style="border: none;" value="{{old('limit',($plan->limit ?? ''))}}" readonly>
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="net_premium" id="net_premium" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('net_premium',($plan->net_premium ?? ''))}}" readonly required>
                                        </div>
                                    </td> -->
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="fees" id="fees" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('fees',($plan->fees ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="stamps" id="stamps" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('stamps',($plan->stamps ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="sales_tax" id="sales_tax" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('sales_tax',($plan->sales_tax ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="cbj" style="border: none;" class="cover_premium positive_number_only_2_decimal" id="cbj_sales_tax" required>
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="gross_premium" id="gross_premium" style="border: none;" value="{{old('gross_premium',($plan->gross_premium ?? ''))}}" readonly required>
                                        </div>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.commissions_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th>{{__('messages.plans.net_premium')}}</th> -->
                                    <th>{{__('messages.plans.commission_percentage')}}</th>
                                    <!-- <th>{{__('messages.plans.commission_amount')}}</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- <input type="hidden" id="net_premium1" style="border: none;" value="{{old('net_premium',($plan->net_premium ?? ''))}}" readonly>
                                    <input type="hidden" name="commission_amount" id="commission_amount" style="border: none;" value="{{old('commission_amount',($plan->commission_amount ?? 0))}}" required readonly> --}}

                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="net_premium1" style="border: none;" value="{{old('net_premium',($plan->net_premium ?? ''))}}" readonly>
                                        </div>
                                    </td> -->

                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_percentage" id="commission_percentage" class="positive_number_only_2_decimal" style="border: none;" value="{{old('commission_percentage',($plan->commission_percentage ?? ''))}}" required>
                                        </div>
                                    </td>

                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_amount" id="commission_amount" style="border: none;" value="{{old('commission_amount',($plan->commission_amount ?? ''))}}" required readonly>
                                        </div>
                                    </td> -->

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
<script>
    $(document).ready(function() {
        $('#restricted_country_ids').on('change', function() {
            let countryIds = $(this).val(); // get selected country ids

            if (countryIds.length === 0) {
                $('#restricted_city_ids').empty().trigger('change');
                return;
            }

            $.ajax({
                url: '{{ route("get.cities.by.countries") }}', // You should create this route
                method: 'POST',
                data: {
                    country_ids: countryIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#restricted_city_ids').empty(); // Clear existing options

                    $.each(response.cities, function(id, name) {
                        $('#restricted_city_ids').append(`<option value="${id}">${name}</option>`);
                    });

                    $('#restricted_city_ids').trigger('change');
                },
                error: function() {
                    alert('Unable to fetch cities. Please try again.');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#restricted_city_ids').on('change', function() {
            let cityIds = $(this).val();

            if (cityIds.length === 0) {
                $('#restricted_district_ids').empty().trigger('change');
                return;
            }

            $.ajax({
                url: '{{ route("get.districts.by.cities") }}', // Create this route
                method: 'POST',
                data: {
                    city_ids: cityIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#restricted_district_ids').empty(); // Clear existing districts

                    $.each(response.districts, function(id, name) {
                        $('#restricted_district_ids').append(`<option value="${id}">${name}</option>`);
                    });

                    $('#restricted_district_ids').trigger('change');
                },
                error: function() {
                    alert('Unable to fetch districts. Please try again.');
                }
            });
        });
    });
</script>
<!-- <script>
    $(document).ready(function() {
        function fetchCBJ(companyId) {
            if (companyId) {
                $.ajax({
                    url: '{{ route("get.cbj") }}',
                    type: 'GET',
                    data: {
                        insurance_company_id: companyId
                    },
                    success: function(response) {
                        $('input[name="cbj"]').val(response.cbj);
                    },
                    error: function() {
                        alert('Failed to fetch cbj value.');
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
</script> -->
<script src="{{asset('js/travel_plan.js?v=1')}}"></script>
@endsection