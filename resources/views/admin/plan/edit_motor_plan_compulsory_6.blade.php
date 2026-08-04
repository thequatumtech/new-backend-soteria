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
                {{__('messages.plans.edit_compulsory_6_plan')}}
                @else
                {{__('messages.plans.add_compulsory_6_plan')}}
                @endif
            </div>
        </div>
        <form action="{{route('motor_plan.save')}}" id="add_motor_plan" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="plan_id" value="{{isset($plan)?$plan->id:null}}">
            <input type="hidden" id="form_type" name="form_type" value="{{isset($plan)?'edit':'add'}}">
            <input type="hidden" id="motor_plan_id" name="motor_plan_id" value="3">
            <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                <div class="row p-3" style="color: #92959A;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.insurance_company')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" style="border: none;" disabled value="{{$plan->insurance_company->company_name ?? ''}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.line_of_business')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" style="border: none;" disabled value="{{$plan->line_of_business->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{__('messages.plans.motor_plan_name')}}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="plan_name" style="border: none;" required value="{{old('plan_name', $plan->plan_name ?? '')}}">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{ __('messages.plans.policy_period') }}</h3>

                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.max_days') }}</div>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="number"
                                        id="policy_period"
                                        name="policy_period"
                                        class="form-control number"
                                        max="4745"
                                        required
                                        style="border: none;"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        value="{{ old('policy_period', $plan->policy_period ?? '') }}">
                                </div>
                            </div>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.insurance_policy_wording')}}</h3>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    {{__('messages.plans.policy_wording_text')}}
                                </div>
                                <textarea name="insurance_policy_text" class="ceditor" id="insurance_policy_text">{{old('insurance_policy_text', $plan->insurance_policy_text ?? '')}}</textarea>
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
                                <div>{{ __('messages.plans.policy_holder_age_restriction') }}</div>
                                @php
                                $restrictedAgeIds = old('restricted_age_ids', json_decode($plan->restricted_age_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="restricted_age_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple>
                                    @foreach ($ages as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $restrictedAgeIds) ? 'selected' : '' }}>
                                        {{ $single->age }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.vehicle_type_restriction') }}</div>
                                @php
                                $restrictedVehicleTypeIds = old('restricted_vehicle_type_ids', json_decode($plan->restricted_vehicle_type_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="restricted_vehicle_type_ids[]"
                                    class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple>
                                    @foreach ($vehicle_types as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $restrictedVehicleTypeIds) ? 'selected' : '' }}>
                                        {{ $single->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.vehicle_brand_restriction') }}</div>
                                @php
                                $restrictedVehicleBrandIds = old('restricted_vehicle_brand_ids', json_decode($plan->restricted_vehicle_brand_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="restricted_vehicle_brand_ids[]"
                                    class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple>
                                    @foreach ($vehicle_brands as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $restrictedVehicleBrandIds) ? 'selected' : '' }}>
                                        {{ $single->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.vehicle_category_restriction') }}</div>
                                @php
                                $restrictedVehicleCategoryIds = old('restricted_vehicle_category_ids', json_decode($plan->restricted_vehicle_category_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="restricted_vehicle_category_ids[]"
                                    class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple>
                                    @foreach ($vehicle_categories as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $restrictedVehicleCategoryIds) ? 'selected' : '' }}>
                                        {{ $single->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.engine_type_restriction') }}</div>
                                @php
                                $restrictedEngineTypeIds = old('restricted_engine_type_ids', json_decode($plan->restricted_engine_type_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="restricted_engine_type_ids[]"
                                    class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple>
                                    @foreach ($engine_types as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $restrictedEngineTypeIds) ? 'selected' : '' }}>
                                        {{ $single->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                <div>{{ __('messages.plans.claim_deductible') }}</div>
                                @php
                                $claimDeductibleIds = old('claim_deductible_ids', json_decode($plan->claim_deductible_ids ?? '[]')) ?? [];
                                @endphp
                                <select name="claim_deductible_ids[]"
                                    class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2"
                                    style="height: 3.5rem;" multiple required>
                                    @foreach ($claim_deductibles as $single)
                                    <option value="{{ $single->id }}"
                                        {{ in_array($single->id, $claimDeductibleIds) ? 'selected' : '' }}>
                                        {{ $single->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h3 class="pt-5">{{__('messages.plans.plan_conditions')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.no_of_points')}}</th>
                                    <th>{{__('messages.plans.net_premium_increase_ticket_points')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=0;$i<=16;$i++)
                                    <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="increase_in_net_premium[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                @php
                                                $matchingCondition=$plan->plan_conditions->firstWhere('no_of_points', $i);
                                            $increaseInNetPremium = $matchingCondition ? $matchingCondition->increase_in_net_premium : 0;
                                            @endphp
                                            value="{{old('increase_in_net_premium['.$i.']', $increaseInNetPremium)}}">
                                        </div>
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.net_premium_increase_accidents')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.no_of_accidents')}}</th>
                                    <th>{{__('messages.plans.cur_year')}}</th>
                                    <th>{{__('messages.plans.one_year')}}</th>
                                    <th>{{__('messages.plans.two_year')}}</th>
                                    <th>{{__('messages.plans.three_year')}}</th>
                                    <th>{{__('messages.plans.four_year')}}</th>
                                    <th>{{__('messages.plans.five_year')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=0;$i<=10;$i++)
                                    @php
                                    $matchingCondition=$plan->net_premium_increase_percentages->firstWhere('no_of_accidents', $i);
                                    $current_year_increase = $matchingCondition ? $matchingCondition->current_year_increase : 0;
                                    $first_year_increase = $matchingCondition ? $matchingCondition->first_year_increase : 0;
                                    $second_year_increase = $matchingCondition ? $matchingCondition->second_year_increase : 0;
                                    $third_year_increase = $matchingCondition ? $matchingCondition->third_year_increase : 0;
                                    $fourth_year_increase = $matchingCondition ? $matchingCondition->fourth_year_increase : 0;
                                    $fifth_year_increase = $matchingCondition ? $matchingCondition->fifth_year_increase : 0;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="current_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('current_year_increase['.$i.']', $current_year_increase)}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="first_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('first_year_increase['.$i.']', $first_year_increase)}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="second_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('second_year_increase['.$i.']', $second_year_increase)}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="third_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('third_year_increase['.$i.']', $third_year_increase)}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="fourth_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('fourth_year_increase['.$i.']', $fourth_year_increase)}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="fifth_year_increase[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('fifth_year_increase['.$i.']', $fifth_year_increase)}}">
                                            </div>
                                        </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.discount')}}</h3>
                        <table class="table-bordered" style="width: 50%;">
                            <thead>
                                <tr>
                                    <th colspan="2">{{__('messages.plans.no_claim_discount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=1;$i<=10;$i++)
                                    @php
                                    $matchingCondition=$plan->no_claim_discounts->firstWhere('year', $i);
                                    $no_claim_discount = $matchingCondition ? $matchingCondition->discount : 0;
                                    @endphp
                                    <tr>
                                        <td width="50%"> Year {{$i}}</td>
                                        <td width="50%">
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="discount[{{$i}}]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                    value="{{old('discount['.$i.']', $no_claim_discount)}}">
                                            </div>
                                        </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.add_policy_covers')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.name_of_cover')}}</th>
                                    <th>{{__('messages.plans.limit')}}</th>
                                    <!-- <th>{{__('messages.plans.deductible')}}</th> -->
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('policy_covers'))
                                @foreach(old('policy_covers') as $index => $policyCover)
                                <tr data-id="{{ $index }}">
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
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_deductible]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" value="{{ old('policy_covers.'.$index.'.cover_deductible') }}" style="border: none;" required>
                                        </div>
                                    </td> -->
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
                                <tr data-id="{{ $index }}">
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_name]" value="{{ $single->cover_name }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ $single->cover_limit }}" style="border: none;" required>
                                            <!-- {{-- <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ $single->cover_limit }}" style="border: none;" required> --}} -->
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[{{ $index }}][cover_deductible]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" value="{{ $single->cover_deductible }}" style="border: none;" required>
                                        </div>
                                    </td> -->
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{ $plan->policy_covers->count() }}" class="add-more-cell">
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
                                            <input type="text" name="policy_covers[0][cover_limit]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="policy_covers[0][cover_deductible]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required>
                                        </div>
                                    </td> -->
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

                        <h3 class="pt-5">{{__('messages.plans.add_additional_benefits')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.name_of_benefit')}}</th>
                                    <th>{{__('messages.plans.limit')}}</th>
                                    <th>{{__('messages.plans.deductible')}}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('additional_benefits'))
                                @foreach(old('additional_benefits') as $index => $policyCover)
                                <tr data-id="{{ $index }}">
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[{{$index}}][benefit_name]" value="{{ old('additional_benefits.'.$index.'.benefit_name') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[{{$index}}][benefit_limit]" value="{{ old('additional_benefits.'.$index.'.benefit_limit') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[{{$index}}][benefit_deductible]" value="{{ old('additional_benefits.'.$index.'.benefit_deductible') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{count(old('additional_benefits', []))}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif($plan->additional_benefits->count() > 0)
                                @foreach($plan->additional_benefits as $index => $single)
                                <tr>
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[{{$index}}][benefit_name]" value="{{$single->benefit_name}}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text"
                                                name="additional_benefits[{{ $index }}][benefit_limit]"
                                                value="{{ $single->benefit_limit }}"
                                                style="border: none;"
                                                required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[{{$index}}][benefit_deductible]" value="{{$single->benefit_deductible}}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    <td rowspan="{{$plan->additional_benefits->count()}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                </tr>
                                @endforeach
                                @elseif(count(old('additional_benefits', [])) == 0)
                                <tr>
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[0][benefit_name]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[0][benefit_limit]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[0][benefit_deductible]" style="border: none;" required>
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


                        <h3 class="pt-5">{{__('messages.plans.six_month_schedule')}}</h3>
                        <table class="table-bordered dynamic-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.vehicle_type')}}</th>
                                    <th>{{__('messages.plans.insurance_premium')}}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('compulsory_schedule_6_months'))
                                @foreach(old('compulsory_schedule_6_months') as $index => $item)
                                <tr data-id="{{ $index }}">
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[{{ $index }}][vehicle_type]" value="{{ old('compulsory_schedule_6_months.'.$index.'.vehicle_type') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[{{ $index }}][premium]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" value="{{ old('compulsory_schedule_6_months.'.$index.'.premium') }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{count(old('compulsory_schedule_6_months', []))}}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @elseif(isset($compulsory_premiums_6) && $compulsory_premiums_6->count() > 0)
                                @foreach($compulsory_premiums_6 as $index => $item)
                                <tr data-id="{{ $index }}">
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[{{ $index }}][vehicle_type]" value="{{ $item->vehicle_type ?? '' }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[{{ $index }}][premium]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" value="{{ $item->premium ?? '' }}" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
                                        <button class="btn p-0 m-0 deletebtn" title="Delete"><img src="{{asset('img/icon-delete.png')}}" alt=""></button>
                                    </td>
                                    @if ($loop->first)
                                    <td rowspan="{{ $compulsory_premiums_6->count() }}" class="add-more-cell">
                                        <button class="btn add_more_btn" title="Add More">Add More</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                <tr data-id="0">
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[0][vehicle_type]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_6_months[0][premium]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" required>
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

                        <h3 class="pt-5">{{__('messages.plans.premium_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    {{-- <th width="20%">{{__('messages.plans.plan_name')}}</th> --}}
                                    <th>{{__('messages.plans.fees')}}</th>
                                    <th>{{__('messages.plans.stamps')}}</th>
                                    <th>{{__('messages.plans.sales_tax')}}</th>
                                    <th>{{__('messages.plans.cbj')}}</th>
                                    <th>{{__('messages.plans.sales_tax_on_cbj')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="fees" id="fees" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('fees',($plan->fees->fees ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="stamps" id="stamps" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('stamps',($plan->fees->stamps ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="sales_tax" id="sales_tax" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('sales_tax',($plan->fees->sales_tax ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="cbj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" value="{{old('cbj',($plan->fees->cbj ?? ''))}}" class="calc_gross_premium positive_number_only_2_decimal" id="cbj" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="salextaxcbj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" value="{{old('salextaxcbj',($plan->fees->sales_tax_cbj ?? ''))}}" class="calc_gross_premium positive_number_only_2_decimal" id="cbj_sales_tax" required>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h3 class="pt-5">{{__('messages.plans.commissions_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{__('messages.plans.net_premium')}}</th>
                                    <th>{{__('messages.plans.commission_percentage')}}</th>
                                    <th>{{__('messages.plans.commission_amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="net_premium" id="net_premium" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" class="calc_gross_premium" style="border: none;" value="{{old('net_premium',($plan->fees->net_premium ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_percentage" id="commission_percentage" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" value="{{old('commission_percentage', ($plan->fees->commission_percentage ?? ''))}}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" style="border: none;" value="{{old('commission_amount', ($plan->fees->commission_amount ?? ''))}}" required>
                                        </div>
                                    </td>
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
</script>
<!-- <script>
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
                        line_of_business_id:6
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

        // On dropdown change
        $('select[name="insurance_company_id"]').on('change', function() {
            let companyId = $(this).val();
            fetchCBJ(companyId);
        });
    });
</script>
<script src="{{asset('js/common_motor_plan.js')}}"></script>
@endsection