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
                    {{__('messages.plans.edit_personal_accident_plan')}}
                </div>
            </div>
            <form action="{{route('personal_accident_plan.save')}}" id="add_motor_plan" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan_id" value="{{isset($plan) ? $plan->id : null}}">
                <input type="hidden" id="form_type" name="form_type" value="edit">
                <div class="body bg-white py-4 px-4 d-flex flex-column gap-3 pb-5">
                    <div class="row p-3" style="color: #92959A;">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.insurance_company')}}</div>
                                    {{-- <input type="text" style="border: none;" disabled value="{{$plan->insurance_company->company_name}}">--}}
                                    <select name="insurance_company_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled {{(!old('insurance_company_id') && !isset($plan->insurance_company_id) ? 'selected' : '')}} hidden>--Select--</option>
                                        @foreach($insurance_companies as $single)
                                        <option value="{{$single->id}}" {{(old('insurance_company_id') && old('insurance_company_id') == $single->id) ? 'selected' : (isset($plan->insurance_company_id) && $plan->insurance_company_id == $single->id ? 'selected' : '')}}>{{$single->company_name}}</option>
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
                                        <input type="text" name="plan_name" style="border: none;" required value="{{old('plan_name') ?: ($plan->plan_name ?? '')}}">
                                    </div>
                                </div>
                            </div>
                            <!-- <h3 class="pt-5">{{__('messages.plans.personal_policy_period')}}</h3>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.insurance_period')}}</div>
                                    <select name="insurance_period_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                        <option value="" disabled {{(!old('insurance_period_id') && !isset($plan->insurance_period_id) ? 'selected' : '')}} hidden>--Select--</option>
                                        @foreach($insurance_periods as $single)
                                        <option value="{{$single->id}}" {{(old('insurance_period_id') && old('insurance_period_id') == $single->id) ? 'selected':(isset($plan->insurance_period_id) && $plan->insurance_period_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
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
                                    <a id="v_insurance_policy_pdf" href="{{asset('uploads/insurance_plans') . '/' . $plan->id . '/' . $plan->insurance_policy_pdf}}"
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
                                    <select name="restricted_country_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                        @foreach($countries as $single)
                                        <option value="{{$single->id}}"
                                            {{ in_array($single->id, old('restricted_country_ids', !empty($plan->restricted_country_ids) ? json_decode($plan->restricted_country_ids) : [])) ? 'selected' : '' }}>
                                            {{$single->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_city_restriction')}}</div>
                                    <select name="restricted_city_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                        @foreach($cities as $single)
                                        <option value="{{$single->id}}"
                                            {{ in_array($single->id, old('restricted_city_ids', !empty($plan->restricted_city_ids) ? json_decode($plan->restricted_city_ids) : [])) ? 'selected' : '' }}>
                                            {{$single->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_district_restriction')}}</div>
                                    <select name="restricted_district_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                        @foreach($districts as $single)
                                        <option value="{{$single->id}}"
                                            {{ in_array($single->id, old('restricted_district_ids', !empty($plan->restricted_district_ids) ? json_decode($plan->restricted_district_ids) : [])) ? 'selected' : '' }}>
                                            {{$single->name}}
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
                                                {{$single->age}} {{$single->type}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_occupation_restriction')}}</div>
                                    <select name="restricted_occupation_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                        @foreach($occupations as $single)
                                        <option value="{{$single->id}}"
                                            {{ in_array($single->id, old('restricted_occupation_ids', !empty($plan->restricted_occupation_ids) ? json_decode($plan->restricted_occupation_ids) : [])) ? 'selected' : '' }}>
                                            {{$single->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_dangerous_activities_restriction')}}</div>
                                    <select name="restricted_dangerous_activities_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" multiple>
                                        @foreach($dangerous_activities as $single)
                                        <option value="{{$single->id}}"
                                            {{ in_array($single->id, old('restricted_dangerous_activities_ids', !empty($plan->restricted_dangerous_activities_ids) ? json_decode($plan->restricted_dangerous_activities_ids) : [])) ? 'selected' : '' }}>
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
                                                <input type="text" name="policy_covers[{{ $index }}][cover_name]" value="{{ old('policy_covers.' . $index . '.cover_name') }}" style="border: none;" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="policy_covers[{{ $index }}][cover_limit]" value="{{ old('policy_covers.' . $index . '.cover_limit') }}" style="border: none;" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="policy_covers[{{ $index }}][cover_deductible]" value="{{ old('policy_covers.' . $index . '.cover_deductible') }}" style="border: none;" required>
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

                            <h3 class="pt-5">{{__('messages.plans.insurance_limit')}}</h3>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.coverage_amount')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="limit" style="border: none;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').split('.').slice(0, 2).join('.');" required value="{{old('limit') ?: ($plan->limit ?? '')}}">
                                    </div>
                                </div>
                            </div>


                        <h3 class="pt-5">{{ __('messages.plans.pricing_schedule') }}</h3>

                        <div style="overflow-x: auto;">
                            <table class="table-bordered" style="width: 100%; min-width: 1000px;">

                                <thead>
                                    <tr>
                                        <th colspan="{{ count($insurance_periods) + 1 }}">
                                            {{ __('messages.plans.pricing_schedule') }}
                                        </th>
                                    </tr>

                                    <tr>
                                        <th width="80px">
                                            {{ __('messages.plans.age_period') }}
                                        </th>

                                        @foreach($insurance_periods as $period)
                                            <th>{{ $period->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>

                                    @for ($i = 1; $i <= 60; $i++)

                                        @php
        $pricing = $plan->pricing_schedule->firstWhere('age', $i);
                                        @endphp

                                        <tr>

                                            {{-- AGE --}}
                                            <td>
                                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">

                                                    <input
                                                        type="text"
                                                        value="{{ $i }}"
                                                        readonly
                                                        style="border: none;"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="pricing_schedule[{{ $i }}][age]"
                                                        value="{{ $i }}"
                                                    >

                                                </div>
                                            </td>

                                            {{-- MONTH VALUES --}}
                                              @foreach($insurance_periods as $period)

                                                @php
            $col = 'm_' . preg_replace('/[^0-9]/', '', $period->name);

            // Try direct column first, then fall back to json_data
            $value = $pricing?->{$col} ?? '';
            if ($value === '' && $pricing && $pricing->json_data) {
                $jsonData = is_array($pricing->json_data) ? $pricing->json_data : json_decode($pricing->json_data, true);
                $value = $jsonData[$col] ?? '';
            }
            // Normalize: strip trailing zeros (2.00 → 2, 2.50 → 2.5)
            if ($value !== '' && is_numeric($value)) {
                $value = floatval($value) + 0;
            }
                                                @endphp

                                                <td>
                                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">

                                                        <input
                                                            type="text"
                                                            name="pricing_schedule[{{ $i }}][{{ $col }}]"
                                                            value="{{ $value }}"
                                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                            style="border: none;"
                                                            required
                                                        >

                                                    </div>
                                                </td>

                                            @endforeach

                                        </tr>

                                    @endfor

                                </tbody>

                            </table>
                        </div>
                        <h3 class="pt-5">{{__('messages.plans.premium_calculations')}}</h3>
                        <table class="table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="20%">{{__('messages.plans.plan_name')}}</th>
                                    <th>{{__('messages.plans.limit')}}</th>
                                    {{-- <th>{{__('messages.plans.net_premium')}}</th> --}}
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
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="calc_plan_name" style="border: none;" value="{{old('plan_name', ($plan->plan_name ?? ''))}}" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="calc_limit" style="border: none;" value="{{old('limit', ($plan->limit ?? ''))}}" readonly>
                                        </div>
                                    </td>

                                    {{-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="net_premium" id="net_premium" class="calc_gross_premium" style="border: none;" value="{{old('net_premium',($plan->net_premium ?? ''))}}" readonly required>
                    </div>
                    </td> --}}

                    <td>
                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                            <input type="text" name="fees" id="fees" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('fees', ($plan->fees ?? ''))}}" required>
                        </div>
                    </td>
                    <td>
                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                            <input type="text" name="stamps" id="stamps" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('stamps', ($plan->stamps ?? ''))}}" required>
                        </div>
                    </td>
                    <td>
                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                            <input type="text" name="sales_tax" id="sales_tax" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" value="{{old('sales_tax', ($plan->sales_tax ?? ''))}}" required>
                        </div>
                    </td>
                    <td>
                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                            <input type="text" name="cbj" style="border: none;" value="{{old('gross_premium', ($plan->cbj ?? ''))}}" class="calc_gross_premium positive_number_only_2_decimal" id="cbj" required>
                        </div>
                    </td>
                    <td>
                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                            <input type="text" name="salextaxcbj" style="border: none;" value="{{old('gross_premium', ($plan->sales_tax_cbj ?? ''))}}" class="calc_gross_premium positive_number_only_2_decimal" id="cbj_sales_tax" required>
                        </div>
                    </td>

                    {{-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="gross_premium" id="gross_premium" style="border: none;" value="{{old('gross_premium',($plan->gross_premium ?? ''))}}" readonly required>
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

                            {{-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" id="net_premium1" style="border: none;" value="{{old('net_premium',($plan->net_premium ?? ''))}}" readonly>
        </div>
        </td> --}}

        <td>
            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                <input type="text" name="commission_percentage" id="commission_percentage" class="positive_number_only_2_decimal" style="border: none;" value="{{old('commission_percentage', ($plan->commission_percentage ?? ''))}}" required>
            </div>
        </td>

        {{-- <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_amount" id="commission_amount" style="border: none;" value="{{old('commission_amount',($plan->commission_amount ?? ''))}}" required readonly>
        </div>
        </td> --}}

        </tr>
        </tbody>
        </table>

        <div class="row pt-5">
            <div class="col-12 col-lg-9"></div>
            <div class="col-12 col-lg-3">
                <div class="pt-4 " style="border: none;">
                    <button type="submit" class="btn rounded-1 w-100 text-white opacity-50 p-2 submit-btn" style="background-color: #EF7C00;">{{isset($plan) ? __('messages.clients.save') : __('messages.clients.add')}} </button>
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
        function fetchCBJ(companyId) {
            if (companyId) {
                $.ajax({
                    url: '{{ route("get.cbj") }}',
                    type: 'GET',
                    data: {
                        insurance_company_id: companyId,
                        line_of_business_id: 9
                    },
                    success: function(response) {
                        if (!$('input[name="cbj"]').val()) {
                            $('input[name="cbj"]').val(response.cbj ?? '');
                        }
                        if (!$('input[name="fees"]').val()) {
                            $('input[name="fees"]').val(response.insurance_fee ?? '');
                        }
                        if (!$('input[name="stamps"]').val()) {
                            $('input[name="stamps"]').val(response.stamp ?? '');
                        }
                        if (!$('input[name="sales_tax"]').val()) {
                            $('input[name="sales_tax"]').val(response.tax ?? '');
                        }
                        if (!$('input[name="salextaxcbj"]').val()) {
                            $('input[name="salextaxcbj"]').val(response.sales_tax_on_cbj ?? '');
                        }
                        if (!$('#commission_percentage').val()) {
                            $('#commission_percentage').val(response.commission_percentage ?? '');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch values.');
                    }
                });
            }
        }

        $('select[name="insurance_company_id"]').on('change', function() {
            fetchCBJ($(this).val());
        });

        let preselectedCompanyId = $('select[name="insurance_company_id"]').val();
        if (preselectedCompanyId) {
            fetchCBJ(preselectedCompanyId);
        }
    });
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
<!-- <script>
    $(document).ready(function() {
        function fetchCBJ(companyId) {
            if (companyId) {
                $.ajax({
                    url: '{{ route("get.cbj") }}',
                    type: 'GET',
                    data: {
                        insurance_company_id: companyId,
                        line_of_business_id: 9
                    },
                    success: function(response) {
                        // Only set values if fields are empty (so edit values stay intact)
                        if (!$('input[name="cbj"]').val()) {
                            $('input[name="cbj"]').val(response.cbj ?? '');
                        }
                        if (!$('input[name="fees"]').val()) {
                            $('input[name="fees"]').val(response.insurance_fee ?? '');
                        }
                        if (!$('input[name="stamps"]').val()) {
                            $('input[name="stamps"]').val(response.stamp ?? '');
                        }
                        if (!$('input[name="sales_tax"]').val()) {
                            $('input[name="sales_tax"]').val(response.tax ?? '');
                        }
                        if (!$('input[name="salextaxcbj"]').val()) {
                            $('input[name="salextaxcbj"]').val(response.sales_tax_on_cbj ?? '');
                        }
                        if (!$('#commission_percentage').val()) {
                            $('#commission_percentage').val(response.commission_percentage ?? '');
                        }
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
            fetchCBJ(companyId); // Always fetch fresh on change
        });

        // Trigger fetch on page load only if fields are empty
        let preselectedCompanyId = $('select[name="insurance_company_id"]').val();
        if (preselectedCompanyId) {
            fetchCBJ(preselectedCompanyId);
        }
    });
</script> -->
<!-- old script -->
<!-- <script>
    $(document).ready(function() {

        var pricingData = @json($plan -> pricing_schedule ?? []);
        var insurancePeriods = @json($insurance_periods);

        function getMonthColumn(periodId) {
            let period = insurancePeriods.find(p => p.id == periodId);

            if (!period) return 'm_3';

            let month = (period.name.match(/\d+/) || ['3'])[0];

            return 'm_' + month;
        }

        function getPeriodName(periodId) {
            let period = insurancePeriods.find(p => p.id == periodId);

            return period ? period.name : '';
        }

        function renderPricingTable(periodId) {

            let monthColumn = getMonthColumn(periodId);
            let periodName = getPeriodName(periodId);

            // STORE CURRENT INPUT VALUES
            let existingValues = {};

            $('.dynamic-input').each(function(index) {
                existingValues[index + 1] = $(this).val();
            });

            // Update header
            $('#period-th').text(periodName);

            let tbodyHtml = '';

            for (let i = 1; i <= 60; i++) {

                // Use entered value first
                let value = existingValues[i] ?? '';

                // If no entered value then load DB value
                if (value === '') {

                    let pricing = pricingData.find(p => p.age == i);

                    value = pricing && pricing[monthColumn] ?
                        pricing[monthColumn] :
                        '';
                }

                tbodyHtml += `
                    <tr>
                        <td>
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input type="text" value="${i}" readonly style="border: none;">
                                <input type="hidden" name="pricing_schedule[${i}][age]" value="${i}">
                            </div>
                        </td>

                        <td class="selected-period-input">
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input
                                    type="text"
                                    class="dynamic-input numeric-only"
                                    name="pricing_schedule[${i}][${monthColumn}]"
                                    value="${value}"
                                    style="border: none;"
                                    required
                                >
                            </div>
                        </td>
                    </tr>
                `;
            }

            $('#pricing-schedule-tbody').html(tbodyHtml);

            attachNumericValidation();
        }

        function attachNumericValidation() {

            $('.numeric-only').off('input').on('input', function() {

                let value = this.value;

                value = value.replace(/[^0-9.]/g, '');

                let parts = value.split('.');

                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }

                this.value = value;
            });
        }

        $('select[name="insurance_period_id"]').on('change', function() {

            let periodId = $(this).val();

            renderPricingTable(periodId);
        });

        let initialPeriodId = $('select[name="insurance_period_id"]').val();

        if (initialPeriodId) {
            renderPricingTable(initialPeriodId);
        }

    });
</script> -->

<!-- updated script -->
<!-- <script>
    $(document).ready(function() {

        var pricingData = @json($plan -> pricing_schedule ?? []);
        var insurancePeriods = @json($insurance_periods);

        var periodValuesCache = {};

        function getMonthColumn(periodId) {
            let period = insurancePeriods.find(p => p.id == periodId);
            if (!period) return 'm_3';
            let month = (period.name.match(/\d+/) || ['3'])[0];
            return 'm_' + month;
        }

        function getPeriodName(periodId) {
            let period = insurancePeriods.find(p => p.id == periodId);
            return period ? period.name : '';
        }

        function saveCurrentValues(monthColumn) {
            if (!monthColumn) return;
            let values = {};
            $('.dynamic-input').each(function(index) {
                values[index + 1] = $(this).val();
            });
            periodValuesCache[monthColumn] = values;
        }

        function renderPricingTable(periodId, previousMonthColumn) {

            let monthColumn = getMonthColumn(periodId);
            let periodName = getPeriodName(periodId);

            saveCurrentValues(previousMonthColumn);

            $('#period-th').text(periodName);

            let tbodyHtml = '';

            for (let i = 1; i <= 60; i++) {

                let value = '';

                if (periodValuesCache[monthColumn] && periodValuesCache[monthColumn][i] !== undefined) {
                    value = periodValuesCache[monthColumn][i];

                } else {
                    let pricing = pricingData.find(p => p.age == i);
                    value = (pricing && pricing[monthColumn]) ? pricing[monthColumn] : '';
                }

                tbodyHtml += `
                    <tr>
                        <td>
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input type="text" value="${i}" readonly style="border: none;">
                                <input type="hidden" name="pricing_schedule[${i}][age]" value="${i}">
                            </div>
                        </td>
                        <td class="selected-period-input">
                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                <input
                                    type="text"
                                    class="dynamic-input numeric-only"
                                    name="pricing_schedule[${i}][${monthColumn}]"
                                    value="${value}"
                                    style="border: none;"
                                    required
                                >
                            </div>
                        </td>
                    </tr>
                `;
            }

            $('#pricing-schedule-tbody').html(tbodyHtml);
            attachNumericValidation();

            return monthColumn;
        }

        function attachNumericValidation() {
            $('.numeric-only').off('input').on('input', function() {
                let value = this.value.replace(/[^0-9.]/g, '');
                let parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                this.value = value;
            });
        }

        let currentMonthColumn = null;

        $('select[name="insurance_period_id"]').on('change', function() {
            let periodId = $(this).val();
            currentMonthColumn = renderPricingTable(periodId, currentMonthColumn);
        });

        let initialPeriodId = $('select[name="insurance_period_id"]').val();
        if (initialPeriodId) {
            currentMonthColumn = renderPricingTable(initialPeriodId, null);
        }

    }); -->
</script>
<script src="{{asset('js/personal_accident_plan.js')}}"></script>
@endsection
