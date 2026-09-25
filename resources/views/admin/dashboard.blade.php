@extends('layouts.mainlayout')
@section('style')
    <style>
        .card-container {
            width: 350px !important;
            height: 350px !important;
        }
    </style>
@endsection

@section('content')
    <main class="flex-grow-1 pt-5">
        @if(auth()->user()->is_super_admin == 1 || in_array('dashboard',json_decode(auth()->user()->authorized_routes,1)))
            <div class="container-fluid">
                <div class="row p-3 g-0 gap-4 justify-content-center">
                    <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5" style="cursor: pointer;">
                        <div class="card-img-container d-flex align-items-center justify-content-center">
                            <img src="img/card-1.png" alt="">
                        </div>
                        <div class="figures">
                            {{$total_purchased_policy}}
                        </div>
                        <div class="details">
                            {{__('messages.dashboard.total_policies')}}
                        </div>
                    </div>
                    <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5" style="cursor: pointer;">
                        <div class="card-img-container d-flex align-items-center justify-content-center">
                            <img src="img/card-1.png" alt="">
                        </div>
                        <div class="figures">
                            {{$last_month_purchased_policy}}
                        </div>
                        <div class="details">
                            {{__('messages.dashboard.total_policies_last_month')}}
                        </div>
                    </div>
                    <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5" style="cursor: pointer;">
                        <div class="card-img-container d-flex align-items-center justify-content-center">
                            <img src="img/card-1.png" alt="">
                        </div>
                        <div class="figures">
                            {{$total_premium_up_to_date}}
                        </div>
                        <div class="details">
                            {{__('messages.dashboard.total_premium')}}
                        </div>
                    </div>
                </div>

                <div class="row p-3 g-0 gap-4 justify-content-center">
                    <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5">
                        <div class="card-img-container d-flex align-items-center justify-content-center">
                            <img src="img/card-1.png" alt="">
                        </div>
                        <div class="figures">
                            {{$total_premium_last_month}}
                        </div>
                        <div class="details">
                            {{__('messages.dashboard.total_premium_last_month')}}
                        </div>
                    </div>

                    @if($highest_sold_policies_by_type)
                        <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5" style="cursor: pointer;">
                            <div class="card-img-container d-flex align-items-center justify-content-center">
                                <img src="img/card-1.png" alt="">
                            </div>
                            <div class="figures">
                                {{$highest_sold_policies_by_type->total}}
                            </div>
                            <div class="details">
                                <b>{{__('messages.policy_types.'.$highest_sold_policies_by_type->policy_type)}}</b> {{__('messages.dashboard.policies_sold')}}
                            </div>
                        </div>
                    @endif

                    @if($highest_sold_policies_by_company)
                            <div class="col-4 card-container d-flex flex-column gap-2 bg-white align-items-center py-5 px-5" style="cursor: pointer;">
                                <div class="card-img-container d-flex align-items-center justify-content-center">
                                    <img src="img/card-1.png" alt="">
                                </div>
                                <div class="figures">
                                    {{$highest_sold_policies_by_company->total}}
                                </div>
                                <div class="details">
                                    <b>{{ $highest_sold_policies_by_company->insurance_company?->company_name ?? '' }}</b>
                                    {{ __('messages.dashboard.policies_sold') }}
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        @endif
    </main>
@endsection
