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
                    @if(isset($plan))
                        {{__('messages.plans.edit_compulsory_3_plan')}}
                    @else
                        {{__('messages.plans.add_compulsory_3_plan')}}
                    @endif
                </div>
            </div>
            <form action="{{route('motor_plan.save')}}" id="add_motor_plan" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="plan_id" value="{{isset($plan)?$plan->id:null}}">
                <input type="hidden" id="form_type" name="form_type" value="{{isset($plan)?'edit':'add'}}">
                <input type="hidden" id="motor_plan_id" name="motor_plan_id" value="2">
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
                                        </div>
                                    </div>
                                <div class="row">
                                {{--
                                                            <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                                                <div>{{__('messages.plans.motor_plan_type')}}</div>
                                                                <select name="motor_plan_id" class="form-select rounded-0 flex-grow-1 border-0 shadow1" style="height: 3.5rem;" required>
                                                                    <option value="" disabled {{(!old('motor_plan_id') && !isset($plan->motor_plan_id) ? 'selected' : '')}} hidden>--Select--</option>
                                                                    @foreach($motor_plans as $single)
                                                                        <option value="{{$single->id}}" {{(old('motor_plan_id') && old('motor_plan_id') == $single->id) ? 'selected':(isset($plan->motor_plan_id) && $plan->motor_plan_id == $single->id? 'selected':'')}}>{{$single->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                --}}
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.motor_plan_name')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="text" name="plan_name" style="border: none;" required value="{{old('plan_name')?:($plan->plan_name ?? '')}}">
                                    </div>
                                </div>
                            </div>
                            <h3 class="pt-5">{{__('messages.plans.policy_period')}}</h3>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.from_date')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="date" name="start_date" id="start_date" min="{{\Carbon\Carbon::today()->format('Y-m-d')}}" style="border: none; font-weight: 330;" required  value="{{old('start_date')?:($plan->start_date ?? '')}}">
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.to_date')}}</div>
                                    <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                        <input type="date" name="end_date" id="end_date" min="{{\Carbon\Carbon::today()->addYears(1)->format('Y-m-d')}}" style="border: none; font-weight: 330;" required readonly value="{{old('end_date')?:($plan->end_date ?? '')}}">
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
                                    <select name="restricted_country_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($countries as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_country_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_city_restriction')}}</div>
                                    <select name="restricted_city_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($cities as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_city_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_district_restriction')}}</div>
                                    <select name="restricted_district_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($districts as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_district_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.policy_holder_age_restriction')}}</div>
                                    <select name="restricted_age_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($ages as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_age_ids',[]))}}>{{$single->age}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.vehicle_type_restriction')}}</div>
                                    <select name="restricted_vehicle_type_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($vehicle_types as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_vehicle_type_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.vehicle_brand_restriction')}}</div>
                                    <select name="restricted_vehicle_brand_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($vehicle_brands as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_vehicle_brand_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.vehicle_category_restriction')}}</div>
                                    <select name="restricted_vehicle_category_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($vehicle_categories as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_vehicle_category_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.engine_type_restriction')}}</div>
                                    <select name="restricted_engine_type_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($engine_types as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('restricted_engine_type_ids',[]))}}>{{$single->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-6 pt-4 d-flex flex-column">
                                    <div>{{__('messages.plans.claim_deductible')}}</div>
                                    <select name="claim_deductible_ids[]" class="form-select rounded-0 flex-grow-1 border-0 shadow1 select2" style="height: 3.5rem;" required multiple>
                                        @foreach($claim_deductibles as $single)
                                            <option value="{{$single->id}}" {{in_array($single->id,old('claim_deductible_ids',[]))}}>{{$single->name}}</option>
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
                                                <input type="text" name="increase_in_net_premium[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('increase_in_net_premium['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
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
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="current_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('current_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="first_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('first_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="second_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('second_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="third_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('third_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="fourth_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('fourth_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="fifth_year_increase[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('fifth_year_increase['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
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
                                    <tr>
                                        <td width="50%"> Year {{$i}}</td>
                                        <td width="50%">
                                            <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                                <input type="text" name="discount[{{$i}}]" style="border: none;" required placeholder="{{__('messages.plans.example').' '.($i*2.5)}}"
                                                       value="{{old('discount['.$i.']')?:(isset($plan) && isset($plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium) ? $plan->motor_insurance_plan_conditions[$i]->increase_in_net_premium : '')}}">
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
                                    <th>{{__('messages.plans.deductible')}}</th>
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
                                            <input type="text" name="policy_covers[0][cover_limit]" style="border: none;" required >
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
                                <tr>
                                    <td width="50%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[0][benefit_name]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="additional_benefits[0][benefit_limit]" style="border: none;" required >
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
                                </tbody>
                            </table>

                            <h3 class="pt-5">{{__('messages.plans.three_month_schedule')}}</h3>
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
                                <tr>
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_3_months[0][vehicle_type]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="40%">
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="compulsory_schedule_3_months[0][premium]" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td width="5%">
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
                                    <th width="20%">{{__('messages.plans.plan_name')}}</th>
                                    <th>{{__('messages.plans.limit')}}</th>
                                    <th>{{__('messages.plans.net_premium')}}</th>
                                    <th>{{__('messages.plans.fees')}}</th>
                                    <th>{{__('messages.plans.stamps')}}</th>
                                    <th>{{__('messages.plans.sales_tax')}}</th>
                                    <th>cbj</th>
                                    <th>{{__('messages.plans.gross_premium')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                        <tr>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" id="calc_plan_name" style="border: none;" readonly>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" id="calc_limit" style="border: none;" readonly class="positive_number_only_2_decimal">
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="net_premium" id="net_premium" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" readonly required>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="fees" id="fees" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="stamps" id="stamps" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="sales_tax" id="sales_tax" class="calc_gross_premium positive_number_only_2_decimal" style="border: none;" required>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="cbj" style="border: none;" class="cover_premium positive_number_only_2_decimal" id="cbj_sales_tax" required>
                                </div>
                            </td>
                            <td>
                                <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                    <input type="text" name="gross_premium" id="gross_premium" style="border: none;" readonly required class="positive_number_only_2_decimal">
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
                                            <input type="text" name="net_premium" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_percentage" style="border: none;" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shadow1 p-2" style="padding-bottom: 2.5rem;">
                                            <input type="text" name="commission_amount" style="border: none;" required>
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
    <script>
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
</script>
    <script src="{{asset('js/common_motor_plan.js')}}"></script>
@endsection

